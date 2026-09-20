.DEFAULT_GOAL := help
SHELL := /bin/sh

DC      := docker compose
DEV     := $(DC) -f compose.yaml
TEST    := $(DC) -f compose.test.yaml
LEGACY  := $(DC) -f compose.legacy.yaml
LEGACYT := $(DC) -f compose.legacy.yaml --profile test
PROD    := $(DC) -f compose.prod.yaml --env-file .env

# Keeps bind-mounted files owned by you on Linux.
export UID := $(shell id -u)
export GID := $(shell id -g)

## ---- development ---------------------------------------------------------

up: ## Start the dev stack on http://localhost:8080
	$(DEV) up -d --build
	@echo "Dev stack up: http://localhost:8080"

down: ## Stop the dev stack (keeps the database volume)
	$(DEV) down

destroy: ## Stop the dev stack and delete the database volume
	$(DEV) down -v

logs: ## Tail all dev logs
	$(DEV) logs -f

shell: ## Shell inside the dev php container
	$(DEV) exec php sh

db-shell: ## MySQL client against the dev database
	$(DEV) exec db mysql -uhomestead -psecret spelcodes

composer: ## Run composer in dev, e.g. make composer ARGS="require foo/bar"
	$(DEV) exec php composer $(ARGS)

## ---- test ----------------------------------------------------------------

test: ## Run the full suite
	$(TEST) run --rm --build php vendor/bin/phpunit $(ARGS)
	$(TEST) down -v

test-suite: ## Run one suite: make test-suite SUITE=forum
	$(TEST) run --rm php vendor/bin/phpunit --testsuite $(SUITE)
	$(TEST) down -v

test-coverage: ## Run the suite with coverage (pcov enabled for this run only)
	$(TEST) run --rm -e PCOV_ENABLED=1 php \
		php -d pcov.enabled=1 vendor/bin/phpunit --coverage-text
	$(TEST) down -v

test-watch: ## Leave the test database up between runs for fast iteration
	$(TEST) up -d db
	@echo "Test db up. Run: make test-again"

test-again: ## Re-run tests against an already-running test database
	$(TEST) run --rm php vendor/bin/phpunit $(ARGS)

stan: ## Static analysis - catches the !\$$x & N precedence class automatically
	$(TEST) run --rm php vendor/bin/phpstan analyse --memory-limit=512M
	$(TEST) down -v

## ---- legacy (PHP 5.6) - TEMPORARY, delete once 8.4 is green --------------

legacy-up: ## Start the PHP 5.6 stack on http://localhost:8081
	$(LEGACY) up -d --build
	@echo "Legacy stack up: http://localhost:8081"

legacy-down: ## Stop the legacy stack (keeps its database volume)
	$(LEGACY) down

legacy-destroy: ## Stop the legacy stack and delete its database volume
	$(LEGACY) down -v

legacy-logs: ## Tail legacy logs
	$(LEGACY) logs -f

legacy-shell: ## Shell inside the PHP 5.6 container
	$(LEGACY) exec php bash

legacy-test: ## Run the original suite on PHP 5.6 / PHPUnit 5.7
	$(LEGACY) run --rm --build test vendor/bin/phpunit $(ARGS); \
		status=$$?; $(MAKE) --no-print-directory legacy-test-clean; exit $$status

legacy-test-suite: ## One suite on 5.6: make legacy-test-suite SUITE=forum
	$(LEGACY) run --rm test vendor/bin/phpunit --testsuite $(SUITE); \
		status=$$?; $(MAKE) --no-print-directory legacy-test-clean; exit $$status

## ---- fast iteration ------------------------------------------------------
## Start the container once, then re-run the suite in milliseconds. The source
## is bind-mounted, so edits apply immediately with no rebuild.

legacy-test-start: ## Start a long-lived test container for fast repeat runs
	$(LEGACYT) up -d test
	@echo ""
	@echo "  make lt                                  full suite"
	@echo "  make lt ARGS=\"--filter it_adds_a_news\"   one test"
	@echo "  make lt-file FILE=tests/Pages/LoginTest.php"
	@echo "  make legacy-test-stop                    when you're done"

lt: ## Re-run the suite in the running container (no rebuild)
	@$(LEGACYT) exec test vendor/bin/phpunit $(ARGS)

lt-file: ## One file: make lt-file FILE=tests/Pages/LoginTest.php
	@test -n "$(FILE)" || { echo "Set FILE=tests/Pages/<Name>Test.php"; exit 1; }
	@$(LEGACYT) exec test vendor/bin/phpunit $(FILE)

lt-shell: ## Shell inside the running test container
	@$(LEGACYT) exec test bash

legacy-test-stop: ## Stop the long-lived test container and its database
	@$(LEGACYT) rm -fsv test db-test >/dev/null 2>&1 || true
	@echo "Test containers removed."

## --------------------------------------------------------------------------

legacy-test-clean: ## Discard the throwaway test database
	@$(LEGACY) rm -fsv db-test >/dev/null 2>&1 || true

legacy-baseline: ## Record a baseline of the suite's current state to baseline.txt
	$(LEGACY) run --rm --build test vendor/bin/phpunit --testdox \
		> baseline.txt 2>&1 || true
	@$(MAKE) --no-print-directory legacy-test-clean
	@echo "Baseline written to baseline.txt - commit it, then diff against it"
	@echo "after every migration step to see exactly what you changed."

## ---- production ----------------------------------------------------------

prod-build: ## Build and tag both production images
	$(PROD) build

prod-up: ## Start the production stack
	$(PROD) up -d

prod-down: ## Stop the production stack
	$(PROD) down

prod-logs: ## Tail production logs
	$(PROD) logs -f

prod-backup: ## Take an immediate database dump into ./backup
	$(PROD) exec -T db sh -c 'mysqldump --user=root --password="$$MYSQL_ROOT_PASSWORD" \
		--single-transaction --routines --triggers "$$MYSQL_DATABASE"' \
		| gzip > backup/manual-$$(date +%Y%m%d-%H%M%S).sql.gz
	@echo "Dump written to ./backup"

prod-restore: ## Restore a dump: make prod-restore FILE=backup/x.sql.gz
	@test -n "$(FILE)" || { echo "Set FILE=backup/<file>.sql.gz"; exit 1; }
	gunzip -c $(FILE) | $(PROD) exec -T db sh -c \
		'mysql --user=root --password="$$MYSQL_ROOT_PASSWORD" "$$MYSQL_DATABASE"'
	@echo "Restored $(FILE). Now check the site actually works."

help: ## Show this help
	@grep -hE '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-16s\033[0m %s\n", $$1, $$2}'

.PHONY: up down destroy logs shell db-shell composer test test-suite \
        test-coverage test-watch test-again stan legacy-up legacy-down \
        legacy-destroy legacy-logs legacy-shell legacy-test \
        legacy-test-suite legacy-test-clean legacy-test-start lt lt-file \
        lt-shell legacy-test-stop legacy-baseline prod-build prod-up \
        prod-down prod-logs prod-backup prod-restore help
