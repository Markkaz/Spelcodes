# Docker environments

Three stacks, one shared PHP build. Copy everything here into the root of the
Spelcodes repo — the paths assume `compose*.yaml`, `Makefile`, `.dockerignore`
and `.env.example` sit beside `composer.json`, with `docker/` alongside them.

```
compose.legacy.yaml    legacy     nginx + php-fpm 5.6   + mysql 5.7   (temporary)
compose.yaml           dev        nginx + php-fpm(dev)  + mysql 8.4 (persistent)
compose.test.yaml      test       php-fpm(test)         + mysql 8.4 (tmpfs)
compose.prod.yaml      prod       nginx(web) + php-fpm(prod) + mysql + backup

docker/php/Dockerfile    base -> vendor -> dev | test | prod -> web
docker/php56/Dockerfile  legacy -> legacy-test
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

Both stacks use different ports, project names and volumes, so you can run
them side by side and compare a page's output directly.

## The order to work in

1. **`make legacy-up` / `make legacy-test`.** The original code on the original
   PHP, with `ext-mysql` present. This is the only stack where the site
   actually runs today.
2. **`make legacy-baseline`.** Records the current pass/fail state to
   `baseline.txt`. Commit it. Every later step gets diffed against this file,
   so you always know whether a failure is one you introduced or one that was
   already there. Without it you are migrating blind.
3. **Fill the coverage gaps while still on 5.6** — `admin/` has 37 files and no
   tests at all, and it holds the critical findings. Adding tests here is far
   easier against code that runs than against code that fatals.
4. **Then `make up` / `make test` on 8.4.** Everything goes red at once; work
   through it with `baseline.txt` as the definition of done.
5. **Delete the legacy stack.** `compose.legacy.yaml`, `docker/php56/`, the
   `legacy-*` Makefile targets and this section.

**PHP 8.4 does not run this code yet, and that is intentional.** `ext-mysql`
disappeared in PHP 7.0, so all 165 `mysql_*` calls fail on the modern stack.
The images deliberately don't shim it — that would buy two weeks and cost you
the migration anyway. Until the PDO work lands, the 8.4 stacks build and start
fine but the application fatals. That red suite *is* the to-do list.

## The legacy stack, honestly

It is a deliberate, temporary exception to everything else in this directory:

- **PHP 5.6 went EOL in December 2018** and gets no security patches. Never
  expose this stack beyond localhost, and never deploy it.
- **amd64 only.** On Apple Silicon it runs under emulation — slower, but it
  works. `platform: linux/amd64` is pinned in the compose file.
- **Debian Jessie's apt repositories are archived**, so `apt-get update` fails
  inside the image. The Dockerfile avoids apt entirely; if you ever need a
  package, the archive.debian.org workaround is in a comment there.
- **MySQL 5.7, not 8.4.** PHP 5.6's client can't do MySQL 8's default
  `caching_sha2_password`. 5.7 also still defaults to `mysql_native_password`.
- **`sql_mode` is emptied.** MySQL 5.7 turns on `STRICT_TRANS_TABLES` and
  `ONLY_FULL_GROUP_BY` by default; 2005 MySQL did neither, and several legacy
  queries break under them. This setting reproduces the original permissiveness
  and must **not** be carried into the modern stack — the strictness is one of
  the things you want back.
- **`magic_quotes_gpc` is already gone** (removed in PHP 5.4), so even this
  stack isn't a perfect 2005 reproduction. `Includes/slashes.php` exists to
  compensate for it, which means `add()` / `strip()` behave differently here
  than they did on the original server. If a test fails in a way that looks
  like double-escaping, this is why.

### The socket trick

`tests/TestCase.php` hardcodes `host=localhost`, and PHP treats `localhost` as
a **unix socket** connection rather than TCP — so simply pointing it at a `db`
hostname wouldn't work without editing the file. Instead the legacy stack
shares MySQL's socket between containers through a named volume, and
`app-legacy.ini` points `pdo_mysql.default_socket` at it.

Net effect: **the suite runs with zero changes to the repo.** That's the point
— you want the baseline measured against untouched code.

If it misbehaves, fall back to the `DB_HOST` change below. You need that for
the 8.4 stack regardless, so it isn't wasted work.

## Three changes the repo needs before the 8.4 stacks go green

None of these are needed for the legacy stack.

1. **`composer.json`** still says `"php": "~5.6"` and `phpunit ~5.7`. Raise to
   `^8.4` and PHPUnit 11. Until then, build with
   `--build-arg COMPOSER_FLAGS=--ignore-platform-reqs`.

2. **`tests/TestCase.php` hardcodes the database host.** Line 54 reads:

   ```php
   self::$pdo = new \PDO('mysql:host=localhost;dbname=spelcodes', 'homestead', 'secret');
   ```

   Inside Docker the database is `db`, not `localhost`. The credentials already
   match what `compose.test.yaml` provides; only the host is wrong. Make it read
   the environment:

   ```php
   $host = getenv('DB_HOST') ?: '127.0.0.1';
   $name = getenv('DB_NAME') ?: 'spelcodes';
   self::$pdo = new \PDO(
       "mysql:host={$host};dbname={$name}",
       getenv('DB_USER') ?: 'homestead',
       getenv('DB_PASS') ?: 'secret'
   );
   ```

   The fallbacks keep a bare `vendor/bin/phpunit` working outside Docker.

3. **`Includes/connect.php`** hardcodes four `define()`s. Point them at
   `getenv()` with no fallback in production. This is the change that makes the
   prod stack's `${VAR:?required}` guards meaningful; right now the container
   would start happily with no database configuration at all.

## How the four differ

| | legacy | dev | test | prod |
|---|---|---|---|---|
| PHP | 5.6 (EOL) | 8.4 | 8.4 | 8.4 |
| MySQL | 5.7, `sql_mode` empty | 8.4 | 8.4 | 8.4 |
| Source | bind-mounted |  bind-mounted, live | mounted for speed, unmounted in CI | baked into the image |
| Database | named volume + shared socket | named volume, persists | tmpfs, discarded | named volume + nightly dump |
| Errors | rendered to browser | rendered to browser | rendered to runner | logged only, never rendered |
| opcache | default | revalidates every request | revalidates | timestamps off |
| Debugger | none | Xdebug on trigger | pcov on demand | none |
| Root FS | writable | writable | writable | read-only + explicit tmpfs |
| Composer | 2.2 LTS | present | present | absent |
| User | root | `www-data` remapped to your uid | `www-data` | `www-data`, owns nothing writable |

## Document root

`NGINX_ROOT` is an environment variable so one config serves both layouts. It
currently points at the repo root, because the pages still live there. The site
config denies `Classes/`, `Includes/`, `Templates/`, `tests/`, `database/`,
`*.sql`, `*.tpl`, dotfiles and the historic `error_log` / `count.txt` — that's a
stopgap, not a solution. Once Step 0 moves everything behind `public/`, switch
`NGINX_ROOT` to `/var/www/html/public` (prod already assumes this) and the deny
rules become belt-and-braces rather than the only thing standing between the
internet and your source.

## Production notes

- **Never build on the server.** Build and tag in CI, set `APP_IMAGE` and
  `WEB_IMAGE` in `.env`, deploy by pulling. The `build:` blocks are there so
  CI can use the same file, not so you can `up --build` in production.
- **TLS is not handled here.** Put Caddy or a load balancer in front and
  forward `X-Forwarded-Proto`; the nginx config already passes it through so
  `session.cookie_secure` works.
- **Sessions are on tmpfs in the php container.** Fine for one container.
  Before you scale to two, move them to Redis or the database, or logins will
  depend on which container answers.
- **The backup service is the bare minimum.** Nightly `mysqldump` to `./backup`,
  14 days retained. Run `make prod-restore FILE=...` against a scratch stack
  *once* and confirm the site works afterwards. An untested backup is not a
  backup, and this is the step everyone skips.
- **Add monitoring.** Nothing here tells you when the site is down. The
  healthchecks only restart containers; they don't page you.

## Troubleshooting

**`make legacy-test` fails with "The zip extension and unzip/7z commands are
both missing"** — you have an older copy of `docker/php56/Dockerfile`. `php:5.6`
ships without `unzip`, `git` and `ext-zip`, and its Debian release is archived
so `apt-get` can't add them. The current Dockerfile builds `vendor/` in the
`composer:2.2` image and copies it across instead. Rebuild with
`make legacy-test` after updating, or force it with
`docker compose -f compose.legacy.yaml build --no-cache test`.

**Tests pass but `vendor/bin/phpunit` is not found** — a bind mount of the
source over `/var/www/html` hides the `vendor/` directory baked into the image.
Both test stacks layer a named volume (`legacy-vendor`, `test-vendor`) on top
to keep it visible. If you change dependencies, drop that volume so it reseeds:
`docker compose -f compose.legacy.yaml down -v`.

**`make legacy-up` works but pages are blank** — check `make legacy-logs`. The
2005 code sets its own `error_reporting`, and several pages call `die()` with a
Dutch string and no status code, so a blank 200 usually means a failed
permission check rather than a crash.

## CI

`compose.test.yaml` is shaped to be lifted straight into GitHub Actions — a
MySQL service container with the same credentials, then `vendor/bin/phpunit`
and `vendor/bin/phpstan analyse`. Get PHPStan to level 6 early: it catches the
`!$cUser->m_iPermis & 512` precedence bug automatically across all 34 instances,
which is worth more than fixing them by hand, because it stops them coming back.
