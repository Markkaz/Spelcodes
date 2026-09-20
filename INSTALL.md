# Installing the Docker environments into Markkaz/Spelcodes

Everything here goes into the **root of the repository**, beside `composer.json`.
Nothing needs to move; no existing file is overwritten.

---

## Step 1 — Check where you're starting from

```sh
cd /path/to/Spelcodes
git status                  # working tree should be clean
ls composer.json phpunit.xml database/    # confirms you're at the repo root
```

Create a branch, because Step 6 rewrites history:

```sh
git checkout -b docker-environments
```

---

## Step 2 — Extract the archive

If you have `spelcodes-docker.tar.gz`:

```sh
tar -xzf spelcodes-docker.tar.gz --strip-components=1 -C .
```

`--strip-components=1` removes the wrapping `spelcodes-docker/` folder so the
contents land at the repo root. Or copy the files by hand using the tree below.

---

## Step 3 — Confirm the layout

After extracting, your repo root should contain these **19 new files**. Existing
files and folders are shown in grey for orientation.

```
Spelcodes/
├── compose.yaml                             NEW  dev stack (PHP 8.4)
├── compose.test.yaml                        NEW  test stack (PHP 8.4)
├── compose.legacy.yaml                      NEW  legacy stack (PHP 5.6) — temporary
├── compose.prod.yaml                        NEW  production stack
├── Makefile                                 NEW  command shortcuts for all four
├── .dockerignore                            NEW  keeps error_log etc. out of images
├── .env.example                             NEW  copy to .env; required by prod
│
├── docker/                                  NEW  everything below is new
│   ├── README.md                                 the guide: order of work, gotchas
│   │
│   ├── php/                                      PHP 8.4 — dev, test, prod, web
│   │   ├── Dockerfile
│   │   ├── conf.d/
│   │   │   ├── app-dev.ini
│   │   │   ├── app-test.ini
│   │   │   └── app-prod.ini
│   │   └── php-fpm.d/
│   │       └── zz-app.conf
│   │
│   ├── php56/                                    PHP 5.6 — DELETE AFTER MIGRATION
│   │   ├── Dockerfile
│   │   ├── conf.d/
│   │   │   └── app-legacy.ini
│   │   └── php-fpm.d/
│   │       └── zz-app.conf
│   │
│   ├── nginx/
│   │   └── templates/
│   │       └── default.conf.template             ${NGINX_ROOT} is substituted at boot
│   │
│   └── mysql/
│       └── init/
│           └── 10-schema.sh                      loads database/*.sql on first boot
│
├── backup/                                  NEW  create it yourself (Step 4)
│   └── .gitkeep
│
├── composer.json                            existing — edited in Step 7
├── phpunit.xml                              existing
├── database/                                existing — read by 10-schema.sh
├── tests/                                   existing — edited in Step 7
├── Classes/  Includes/  Templates/          existing
├── admin/  forum/  spellen/                 existing
└── *.php                                    existing — the 2005 pages
```

Verify:

```sh
find docker -type f | sort
ls compose*.yaml Makefile .dockerignore .env.example
```

That should list 14 files under `docker/` plus the 6 at the root.

---

## Step 4 — Fix permissions and create the backup folder

The schema script needs its executable bit, and git has to record it — otherwise
it silently won't run on a fresh clone or in CI.

```sh
chmod +x docker/mysql/init/10-schema.sh
git add --chmod=+x docker/mysql/init/10-schema.sh
```

Confirm the mode landed:

```sh
git ls-files -s docker/mysql/init/10-schema.sh
# 100755 <hash> 0   docker/mysql/init/10-schema.sh
```

`100755` is correct. If you see `100644`, the bit didn't stick — re-run the
`git add --chmod=+x` line.

> The file must be staged before git will record a mode change, which is why
> `git add --chmod=+x` is used here rather than `git update-index --chmod=+x`.
> The latter only works on paths already in the index.
>
> On Linux and macOS the `chmod +x` alone is enough, since git reads the mode
> from disk on `git add`. The explicit flag matters on Windows and on
> filesystems that don't carry the executable bit.

```sh
mkdir -p backup && touch backup/.gitkeep
```

---

## Step 5 — Update `.gitignore`

Your current `.gitignore` covers only `.idea` and `vendor`. Append:

```gitignore
# Environment and secrets — never commit
.env

# Database dumps
/backup/*
!/backup/.gitkeep

# Characterization baseline (optional: commit this one deliberately)
baseline.txt
```

---

## Step 6 — Purge the junk that must not reach an image

`.dockerignore` already excludes these from builds, but they should leave the
repo entirely. `error_log` alone is 10.8 MB and contains production paths and
database usernames; `count.txt` holds visitor IP addresses.

```sh
git rm --cached error_log count.txt .ftpquota google78393d5c5a128d89.html
rm -f error_log count.txt .ftpquota google78393d5c5a128d89.html
git commit -m "Remove deployment residue and logs from the working tree"
```

To strip them from history as well, install `git-filter-repo` first. On macOS:

```sh
brew install git-filter-repo
```

`pip` is usually not on the path on macOS, and a Homebrew Python will refuse
with "externally-managed-environment". If you'd rather avoid Homebrew:

```sh
python3 -m pip install --user git-filter-repo   # ensure ~/.local/bin is on PATH
pipx install git-filter-repo                    # or, if you have pipx
```

Then, on your branch. **Commit your Docker work first** — filter-repo hard-resets
the working tree and anything uncommitted is lost:

```sh
git add -A
git commit -m "Add Docker environments for legacy, dev, test and production"
```

```sh
git filter-repo --force --invert-paths \
    --path error_log --path count.txt --path .ftpquota
```

`--force` is required here. filter-repo refuses to run on anything that isn't a
freshly packed clone ("this does not look like a fresh clone"), because it
can't guarantee stale objects won't survive. On a repo where everything is
committed, overriding it is safe and is the documented escape hatch. The
alternative is to push your branch, clone fresh into a temp directory, run
filter-repo there without `--force`, and force-push from that copy.

The old objects linger until garbage collection, so reclaim the space:

```sh
git reflog expire --expire=now --all
git gc --prune=now --aggressive
du -sh .git          # ~15 MB before, ~4 MB after
```

> **This step is optional.** The `git rm --cached` above is what matters day to
> day. The rewrite only matters because `error_log` contains production paths
> and old database usernames and `count.txt` holds visitor IPs — all long dead.
> The stronger argument is size: it removes about 10 MB from every clone.

`git filter-repo` **deletes the `origin` remote** as a safety measure, so
restore it before pushing. Use the same protocol you originally cloned with —
if you cloned over HTTPS, an SSH URL will fail with
`Permission denied (publickey)` because there's no key registered:

```sh
git remote set-url origin https://github.com/Markkaz/Spelcodes.git
# or, if the remote is gone entirely:
git remote add origin https://github.com/Markkaz/Spelcodes.git

git remote -v          # confirm before pushing
git push --force --all
git push --force --tags
```

If HTTPS prompts for a password: GitHub stopped accepting account passwords in
2021. Use a Personal Access Token with `repo` scope (Settings → Developer
settings → Personal access tokens) at the password prompt, or run
`gh auth login` if you have the GitHub CLI.

For SSH instead, register a key first:

```sh
ssh-keygen -t ed25519 -C "your@email"
cat ~/.ssh/id_ed25519.pub     # paste into GitHub → Settings → SSH keys
ssh -T git@github.com         # should greet you by username
```

then use `git@github.com:Markkaz/Spelcodes.git` as the remote URL.

---

## Step 7 — Start the legacy stack (this is the one that works today)

Nothing in the repo needs changing for this. Build and run:

```sh
make legacy-up              # http://localhost:8081
make legacy-test            # original suite, PHP 5.6 / PHPUnit 5.7
make legacy-baseline        # writes baseline.txt — commit it
```

First build pulls `php:5.6-fpm` and `mysql:5.7` and takes a few minutes; on
Apple Silicon both run under emulation.

**If `make legacy-test` can't reach the database**, the socket-sharing trick
didn't take on your platform. Fall back to editing `tests/TestCase.php`:

```php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$name = getenv('DB_NAME') ?: 'spelcodes';
self::$pdo = new \PDO(
    "mysql:host={$host};dbname={$name}",
    getenv('DB_USER') ?: 'homestead',
    getenv('DB_PASS') ?: 'secret'
);
```

The fallbacks keep a bare `vendor/bin/phpunit` working outside Docker, and you
need this change for PHP 8.4 regardless — so it isn't wasted work.

---

## Step 8 — Only later: the PHP 8.4 stacks

These build and start straight away, but the application fatals until the
`mysql_*` calls become PDO. That's expected — it's the migration, not a
misconfiguration.

Before they can go green:

1. `composer.json` — raise `"php": "~5.6"` to `"^8.4"` and PHPUnit to `^11.0`.
   Until then, build with
   `--build-arg COMPOSER_FLAGS=--ignore-platform-reqs`.
2. `tests/TestCase.php` — the `DB_HOST` change from Step 7.
3. `Includes/connect.php` — replace the four `define()`s with `getenv()`.

Then:

```sh
make up                     # http://localhost:8080
make test
make stan                   # PHPStan — catches the !$x & N precedence bugs
```

---

## Step 9 — Production, when you get there

```sh
cp .env.example .env
# fill in APP_IMAGE, WEB_IMAGE, APP_URL and the MySQL passwords
# generate each with: openssl rand -base64 32
```

`compose.prod.yaml` uses `${VAR:?required}`, so it refuses to start with
anything missing rather than silently falling back to a default. Build and tag
images in CI; never build on the server. TLS belongs in front (Caddy or a load
balancer) — the nginx config already forwards `X-Forwarded-Proto`.

---

## Step 10 — Delete the legacy stack

Once `make test` is green on 8.4:

```sh
git rm -r compose.legacy.yaml docker/php56
# then remove the legacy-* targets from the Makefile
# and the legacy sections from docker/README.md
```

Tag the commit before you do, so the before/after stays legible:

```sh
git tag php56-baseline
```

---

## Commands at a glance

| | legacy (5.6) | modern (8.4) |
|---|---|---|
| Start | `make legacy-up` → :8081 | `make up` → :8080 |
| Test | `make legacy-test` | `make test` |
| Shell | `make legacy-shell` | `make shell` |
| Logs | `make legacy-logs` | `make logs` |
| Stop | `make legacy-down` | `make down` |
| Wipe db | `make legacy-destroy` | `make destroy` |

`make help` lists everything, production targets included.
