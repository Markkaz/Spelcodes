# Docker environments

Four stacks. The PHP 8.4 ones share a single base image so they cannot drift
apart; the legacy 5.6 stack is separate and temporary.

```
compose.legacy.yaml    legacy     nginx + php-fpm 5.6   + mysql 5.7   (temporary)
compose.yaml           dev        nginx + php-fpm(dev)  + mysql 8.4 (persistent)
compose.test.yaml      test       php-fpm(test)         + mysql 8.4 (tmpfs)
compose.prod.yaml      prod       nginx(web) + php-fpm(prod) + mysql + backup

docker/php/Dockerfile    base -> vendor -> dev | test | prod -> web
docker/php56/Dockerfile  legacy (fpm) | legacy-vendor | legacy-test (cli)
```

## Quick start

Start on the legacy stack. Get the suite green there first, then migrate.

```sh
make legacy-up           # the 2005 site, running: http://localhost:8081
make legacy-test         # the original suite on PHP 5.6 / PHPUnit 5.7
make legacy-baseline     # record what passes today, commit the result

make up                  # the 8.4 stack: http://localhost:8080
make test
make help                # everything else
```

Both stacks use different ports, project names and volumes, so you can run them
side by side and compare a page's output directly.

## The order to work in

1. **`make legacy-up` / `make legacy-test`.** The original code on the original
   PHP, with `ext-mysql` present. This is the only stack where the site
   actually runs today.
2. **`make legacy-baseline`.** Records the current pass/fail state to
   `baseline.txt`. Commit it. Every later step gets diffed against this file,
   so you always know whether a failure is one you introduced or one that was
   already there.
3. **Fill the coverage gaps while still on 5.6** — see *Coverage* below.
   `admin/` has 37 files and no tests at all, and it holds the critical
   findings. Adding tests is far easier against code that runs.
4. **Then `make up` / `make test` on 8.4.** Everything goes red at once; work
   through it with `baseline.txt` as the definition of done.
5. **Delete the legacy stack.** `compose.legacy.yaml`, `docker/php56/`, the
   `legacy-*` and `lt-*` Makefile targets, and this section.

**PHP 8.4 does not run this code yet, and that is intentional.** `ext-mysql`
disappeared in PHP 7.0, so all 165 `mysql_*` calls fail on the modern stack.
The images deliberately don't shim it — that would buy two weeks and cost you
the migration anyway. That red suite *is* the to-do list.

## Configuration

Database settings come from the environment. `Includes/connect.php` is the
single source of truth; `tests/TestCase.php` requires it and reuses its
constants, so the harness and the pages under test cannot disagree.

| Variable | Required | Default |
|---|---|---|
| `DB_HOST` | yes | — |
| `DB_NAME` | yes | — |
| `DB_USER` | yes | — |
| `DB_PASS` | yes | — |
| `DB_PORT` | no | `3306` |

Precedence: real environment variables first, then `.env` in the repo root.
`envLoad()` never overwrites a variable that is already set, so Docker always
wins over a stray `.env` file. All four compose stacks inject these, so `.env`
is only needed for runs outside Docker.

**php-fpm clears the environment by default**, so both pool configs
(`docker/php/php-fpm.d/zz-app.conf` and `docker/php56/php-fpm.d/zz-app.conf`)
forward the variables explicitly with `env[DB_HOST] = $DB_HOST` and friends.
Without that, `getenv()` returns `false` inside a page. The CLI test container
inherits its environment normally, so tests would have passed while the site
connected somewhere else entirely — which is exactly why the variables are
forwarded explicitly rather than trusting `clear_env`.

Do **not** set these in `phpunit.xml`. PHPUnit 5.7 calls `putenv()`
unconditionally for `<env>`, so it would override the `DB_HOST=db-test` that
Compose provides. The `force` attribute that makes this safe arrived in
PHPUnit 8; revisit after the upgrade.

## The legacy stack, honestly

A deliberate, temporary exception to everything else here:

- **PHP 5.6 went EOL in December 2018** and gets no security patches. Never
  expose this stack beyond localhost, and never deploy it.
- **amd64 only.** On Apple Silicon it runs under emulation — slower, but it
  works. `platform: linux/amd64` is pinned in the compose file.
- **Debian's repositories for this release are archived.** The `legacy` stage
  avoids apt entirely. The `legacy-test` stage cannot (see *Coverage*).
- **Composer runs elsewhere.** `php:5.6` ships without `unzip`, `git` and
  `ext-zip`, so it cannot unpack a single package. `vendor/` is built in the
  `composer:2.2` image and copied across.
- **MySQL 5.7, not 8.4.** PHP 5.6's client can't do MySQL 8's default
  `caching_sha2_password`.
- **`sql_mode` is emptied.** MySQL 5.7 turns on `STRICT_TRANS_TABLES` and
  `ONLY_FULL_GROUP_BY` by default; 2005 MySQL did neither, and several legacy
  queries break under them. Must **not** be carried into the modern stack — the
  strictness is one of the things you want back.
- **`magic_quotes_gpc` is already gone** (removed in PHP 5.4), so even this
  isn't a perfect 2005 reproduction. `Includes/slashes.php` exists to
  compensate for it, so `add()` / `strip()` behave differently here than on the
  original server. A failure that looks like double-escaping traces back to
  this.
- **Two database servers.** `db` backs the running site and gets
  `database/*.sql` loaded. `db-test` is deliberately empty on tmpfs, because
  `tests/TestCase.php` creates and drops its own tables.

## Fast iteration

`make legacy-test` rebuilds the image and tears everything down afterwards,
which is right for a baseline run and much too slow for a red/green loop. For
iterating, start the container once and `exec` into it:

```sh
make legacy-test-start                          # once
make lt                                         # full suite, no rebuild
make lt ARGS="--filter it_adds_a_news_comment"  # one test
make lt-file FILE=tests/Pages/LoginTest.php     # one file
make lt-shell                                   # poke around inside
make legacy-test-stop                           # when you're done
```

The source is bind-mounted, so edits to `tests/` or the PHP pages apply
immediately. Only two things need a restart: changing `composer.json` (run
`make legacy-test-stop` then `make legacy-test` to rebuild `vendor/`), and
changing the Dockerfile or php.ini.

The test database stays up between `make lt` runs. Normally fine, but a crashed
run can leave tables behind and the next run fails with "table already exists".
Clear it with `make legacy-test-clean`.

Use `make legacy-test` — the slow path — whenever you want the number that
counts, and before recording a baseline.

## Coverage

```sh
make lt-coverage         # HTML report in coverage/html, plus a text summary
make legacy-coverage     # one-shot: rebuild, run, tear down
make untested            # application files with no test file at all
```

The report lands on your host via the bind mount; open
`coverage/html/index.html`.

### Why Xdebug, and why the test stage is php-cli

pcov needs PHP 7.1+. phpdbg would have avoided compiling anything, but the
official PHP 5.6 images do not ship it — `/usr/local/bin` contains `php-cgi`
and no `phpdbg`, in both variants. So Xdebug 2.5.5, the last release supporting
5.6, is the only route.

Building it needs a compiler, which needs apt, which needs Debian Stretch's
archived repositories. Stretch is end-of-life: its Release files are past their
Valid-Until date and signed with expired keys, so the Dockerfile relaxes those
checks. Acceptable in a throwaway test image and nowhere else.

The test stage builds `FROM php:5.6-cli`, which is correct regardless — the
test runner is a command-line process and never needed php-fpm. The `legacy`
stage serving the site stays on fpm.

Xdebug costs roughly 2x on ordinary runs. If that starts to bite, comment out
`docker-php-ext-enable xdebug` and load it per-run instead:

```sh
php -d zend_extension=xdebug.so vendor/bin/phpunit --coverage-text
```

### The phpunit.xml whitelist matters more than the tooling

The whitelist used to cover `index.php` and `archief.php`, which made any
coverage figure meaningless. It now covers the whole application, so files with
no tests appear at 0% — which is the gap you are looking for.

**`processUncoveredFilesFromWhitelist` must stay `false`.** When true, PHPUnit
*includes* each uncovered file to measure it, which would execute
`admin/backup.php`, `spelVerwijder.php` and every other destructive page
against your test database. `addUncoveredFilesFromWhitelist="true"` reports
them at 0% via static analysis instead.

### Coverage answers the smaller half of the question

It tells you which lines executed, not whether anything was asserted about
them — and that gap is wide here, because `visitPage()` includes a whole page,
so one test can light up a file while asserting almost nothing. Read it
alongside `make untested` and `vendor/bin/phpunit --testdox`. Where a file has
high coverage but few testdox lines, the coverage is incidental.

Worth checking specifically: `Classes/User.php`'s session path.
`TestCase::login()` only ever forges a cookie, so half the auth logic may be
near-uncovered.

## Document root

`NGINX_ROOT` is an environment variable so one config serves both layouts. It
currently points at the repo root, because the pages still live there. The site
config denies `Classes/`, `Includes/`, `Templates/`, `tests/`, `database/`,
`*.sql`, `*.tpl`, dotfiles and the historic `error_log` / `count.txt` — a
stopgap, not a solution. Once the front controller lands, switch `NGINX_ROOT`
to `/var/www/html/public` (prod already assumes this) and the deny rules become
belt-and-braces rather than the only thing between the internet and your
source.

## How the four differ

| | legacy | dev | test | prod |
|---|---|---|---|---|
| PHP | 5.6 (EOL) | 8.4 | 8.4 | 8.4 |
| SAPI | fpm (site), cli (tests) | fpm | fpm | fpm |
| MySQL | 5.7, `sql_mode` empty | 8.4 | 8.4 | 8.4 |
| Source | bind-mounted | bind-mounted, live | mounted for speed, unmounted in CI | baked into the image |
| Database | `db` seeded + empty `db-test` | named volume, persists | tmpfs, discarded | named volume + nightly dump |
| Errors | rendered to browser | rendered to browser | rendered to runner | logged only, never rendered |
| opcache | default | revalidates every request | revalidates | timestamps off |
| Coverage | Xdebug 2.5.5 | — | pcov on demand | — |
| Debugger | none | Xdebug on trigger | — | none |
| Root FS | writable | writable | writable | read-only + explicit tmpfs |
| Composer | in `legacy-vendor` only | present | present | absent |
| User | root | `www-data` remapped to your uid | `www-data` | `www-data`, owns nothing writable |

## Production notes

- **Never build on the server.** Build and tag in CI, set `APP_IMAGE` and
  `WEB_IMAGE` in `.env`, deploy by pulling. The `build:` blocks exist so CI can
  use the same file.
- **TLS is not handled here.** Put Caddy or a load balancer in front and
  forward `X-Forwarded-Proto`; the nginx config already passes it through so
  `session.cookie_secure` works.
- **Sessions are on tmpfs in the php container.** Fine for one container.
  Before scaling to two, move them to Redis or the database.
- **The backup service is the bare minimum.** Nightly `mysqldump` to `./backup`,
  14 days retained. Run `make prod-restore FILE=...` against a scratch stack
  *once* and confirm the site works afterwards. An untested backup is not a
  backup.
- **Add monitoring.** Nothing here tells you when the site is down. The
  healthchecks only restart containers; they don't page you.
- **Tighten the config defaults.** `Includes/connect.php` still falls back to
  `3306` for the port, which is fine, but a missing `DB_PASS` should fail
  loudly rather than silently. Distinguishing environments properly needs
  `APP_ENV`.

## Troubleshooting

**`make legacy-test` fails with "The zip extension and unzip/7z commands are
both missing"** — you have an older `docker/php56/Dockerfile`. `php:5.6` ships
without `unzip`, `git` and `ext-zip`, and its Debian release is archived so
apt can't add them. The current Dockerfile builds `vendor/` in the
`composer:2.2` image and copies it across.

**Tests pass but `vendor/bin/phpunit` is not found** — a bind mount of the
source over `/var/www/html` hides the `vendor/` baked into the image. Both test
stacks layer a named volume (`legacy-vendor`, `test-vendor`) on top. If you
change dependencies, drop it so it reseeds:
`docker compose -f compose.legacy.yaml down -v`.

**"Base table or view already exists: 1050 Table 'users' already exists"** —
the test database had the schema pre-loaded. `tests/TestCase.php` creates its
own tables and drops them again, so it needs an empty server. That is what
`db-test` is for; only the services backing the *running site* get
`database/*.sql` loaded. After an upgrade, clear stale volumes with
`docker compose -f compose.legacy.yaml down -v`.

**"1217 Cannot delete or update a parent row"** — almost always collateral from
a run that failed earlier. If `setUpBeforeClass()` blows up, `setUp()` never
runs, and `emptyTables()` (which disables foreign key checks on the harness
connection) never executes, so `dropTables()` hits constraints it normally
would not. Run `make legacy-test-clean && make legacy-test`.

Do **not** fix it by disabling foreign keys globally on the server. Several
pages rely on the database to reject invalid input — `addNieuws.php` has no
existence check and returns its 404 purely because the FK insert fails — so a
global `foreign_key_checks = 0` makes genuinely passing tests fail. If the
error survives a clean database, the real fix is dependency-ordered drops in
`TestCase::dropTables()`.

**`phpdbg: executable file not found`** — you have an older Makefile. The PHP
5.6 images don't ship phpdbg; coverage uses Xdebug and calls
`vendor/bin/phpunit` directly.

**`exec` runs the old image after a rebuild** — `exec` targets the existing
container. Recreate it: `make legacy-test-stop && make legacy-test-start`.
Check which one you're in with
`docker compose -f compose.legacy.yaml exec test php -v | head -1`.

**Pages are blank but `make legacy-up` works** — check `make legacy-logs`. The
2005 code sets its own `error_reporting`, and several pages `die()` with a
Dutch string and no status code, so a blank 200 usually means a failed
permission check rather than a crash.

**`getenv("DB_HOST")` is empty inside a page** — the php-fpm pool config didn't
land. Check `docker/php56/php-fpm.d/zz-app.conf` has the `env[...]` lines and
rebuild with `docker compose -f compose.legacy.yaml build --no-cache php`.

## CI

`compose.test.yaml` is shaped to be lifted straight into GitHub Actions — a
MySQL service container with the same credentials, then `vendor/bin/phpunit`
and `vendor/bin/phpstan analyse`. Set `DB_*` as workflow environment variables.
Get PHPStan to level 6 early: it catches the `!$cUser->m_iPermis & 512`
precedence bug automatically across all 34 instances, which stops them coming
back.
