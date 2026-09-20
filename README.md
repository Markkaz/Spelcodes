# Spelcodes

Spelcodes.nl — a Dutch game walkthrough site written in 2005. Currently being
refactored from PHP 5.6 to PHP 8.4 under characterization tests.

Detailed notes: [`docker/README.md`](docker/README.md).

## Setup

Requires Docker Desktop and `make`. Nothing else.

```sh
git clone https://github.com/Markkaz/Spelcodes.git
cd Spelcodes
make help                # list all commands
```

Database credentials come from the environment. All four Docker stacks inject
them, so no setup is needed for `make` commands. To run tests or the site
outside Docker, `cp .env.example .env` — there are no fallbacks in
`Includes/connect.php`, so a missing variable throws.

On Apple Silicon the legacy stack runs under emulation. No action needed.

## Legacy (PHP 5.6) — works today

The original code on the original PHP. Temporary; will be deleted once the
suite is green on 8.4.

```sh
make legacy-up           # site at http://localhost:8081
make legacy-logs
make legacy-down
make legacy-destroy      # also wipes the database
```

Tests:

```sh
make legacy-test         # full run, rebuilds, tears down after
make legacy-baseline     # writes baseline.txt
```

Fast loop — start the container once, then re-run in milliseconds:

```sh
make legacy-test-start
make lt                                         # full suite, no rebuild
make lt ARGS="--filter it_adds_a_news_comment"
make lt-file FILE=tests/Pages/LoginTest.php
make lt-shell
make legacy-test-stop
```

Coverage:

```sh
make lt-coverage         # HTML report in coverage/html
make legacy-coverage     # one-shot, rebuilds and tears down
make untested            # files with no test file at all
```

There is no legacy production environment, by design. PHP 5.6 has been
end-of-life since December 2018 — never expose this stack beyond localhost.

## Modern (PHP 8.4)

Builds and starts, but the application still fatals: `ext-mysql` was removed in
PHP 7.0 and the 165 `mysql_*` calls have not been migrated to PDO yet. This is
the work in progress, not a broken setup.

Dev:

```sh
make up                  # site at http://localhost:8080
make shell
make db-shell
make composer ARGS="require foo/bar"
make logs
make down
make destroy
```

Test:

```sh
make test
make test-suite SUITE=forum
make test-coverage
make stan                # PHPStan
```

Production:

```sh
cp .env.example .env     # fill in every value; the stack refuses to start otherwise
make prod-build          # build in CI, never on the server
make prod-up
make prod-logs
make prod-backup
make prod-restore FILE=backup/spelcodes-20260920-030000.sql.gz
make prod-down
```

TLS is not handled here — put Caddy or a load balancer in front.

## Ports

| | Site | MySQL |
|---|---|---|
| Legacy | 8081 | 33062 |
| Dev | 8080 | 33061 |
| Prod | 8080 (loopback) | not published |

Legacy and dev can run at the same time.
