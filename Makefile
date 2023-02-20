PSALM_DIR        = tools/psalm
PHP_CS_FIXER_DIR = tools/php-cs-fixer
DOCS_DIR         = docs
BUILD_LOG_DIR    = var/log/build
COVERAGE_DIR     = ${DOCS_DIR}/coverage
COVERAGE_LOG_DIR = ${BUILD_LOG_DIR}/coverage

PSALM_BASELINE_FILE = psalm-baseline.xml

COMPOSER       = composer
DOCKER_COMPOSE = docker-compose
PHP            = php
PHPUNIT        = bin/phpunit
PHP_CS_FIXER   = ${PHP_CS_FIXER_DIR}/vendor/bin/php-cs-fixer
PSALM          = ${PSALM_DIR}/vendor/bin/psalm
PSALM_PLUGIN   = ${PSALM_DIR}/vendor/bin/psalm-plugin
CONSOLE        = bin/console
YARN           = yarn
SYMFONY        = symfony

SYMFONY_DEPRECATIONS_HELPER='max[total]=99999&quiet[]=indirect&quiet[]=other'

.DEFAULT_GOAL: help
.PHONY: help assets

help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

diagnostic: # Output various help info
	$(CONSOLE) about

## ---- Project -------------------------------------------------------------------
install: composer-install yarn-install tools-install assets ## Install Dependencies & make assets

assets: ## Compile assets (dev)
	$(YARN) run encore dev

db-migrate: ## Run database migrations
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration -vvv

db-fixtures-load: ## Load Database Fixtures
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(CONSOLE) doctrine:fixtures:load --no-interaction

db-diff: ## Generate DB Migration
	$(CONSOLE) doctrine:migrations:diff --no-interaction -vvv

db-diff-dump: ## View database diff
	$(CONSOLE) doctrine:schema:update --dump-sql --no-interaction -vvv

start: up ## spin up docker containers and symfony server
	$(SYMFONY) server:ca:install --renew --force
	$(SYMFONY) server:start --daemon

stop: down ## spin down docker containers and symfony server
	$(SYMFONY) server:stop

upgrade: composer-upgrade yarn-upgrade tools-upgrade ## Upgrade dependencies

## ---- Docker --------------------------------------------------------------------
up: ## Start the docker hub in detached mode (no logs)
	$(DOCKER_COMPOSE) up --detach --remove-orphans

down: ## Stop the docker hub
	$(DOCKER_COMPOSE) down --remove-orphans

logs: ## Show live logs
	$(DOCKER_COMPOSE) logs --tail=0 --follow

## ---- Composer ------------------------------------------------------------------
composer-install: ## Install composer dependencies
	$(COMPOSER) install --optimize-autoloader

composer-outdated: ## View outdated packages
	$(COMPOSER) outdated --direct

composer-upgrade: ## Upgrade composer packages
	$(COMPOSER) upgrade --with-all-dependencies --optimize-autoloader

## ---- Yarn ----------------------------------------------------------------------
yarn-install: ## Install yarn dependencies
	$(YARN) install

yarn-outdated: ## View outdated packages
	$(YARN) outdated

yarn-upgrade: ## Upgrade yarn packages
	$(YARN) upgrade

## ---- Testing -------------------------------------------------------------------
test: test-unit test-functional ## Run all tests for all apps

test-unit: ## Run unit tests ONLY for all apps
	SYMFONY_DEPRECATIONS_HELPER=$(SYMFONY_DEPRECATIONS_HELPER) XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --testsuite "unit"

test-functional: ## Run functional tests ONLY for all apps
	$(CONSOLE) doctrine:database:drop --env=test -vvv -n --if-exists --force
	$(CONSOLE) doctrine:database:create --env=test -vvv -n --if-not-exists
	$(CONSOLE) doctrine:schema:create --env=test -vvv -n
	$(CONSOLE) doctrine:fixtures:load --env=test -vvv -n || true
	SYMFONY_DEPRECATIONS_HELPER=$(SYMFONY_DEPRECATIONS_HELPER) XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHPUNIT) --testsuite "functional"

## ---- Documentation -------------------------------------------------------------
coverage: ## Generate Code Coverage
	rm -rf ${COVERAGE_DIR}
	mkdir -vp ${COVERAGE_DIR}
	$(CONSOLE) doctrine:database:drop --env=test -vvv -n --if-exists --force
	$(CONSOLE) doctrine:database:create --env=test -vvv -n --if-not-exists
	$(CONSOLE) doctrine:schema:create --env=test -vvv -n
	$(CONSOLE) doctrine:fixtures:load --env=test -vvv -n || true
	SYMFONY_DEPRECATIONS_HELPER=$(SYMFONY_DEPRECATIONS_HELPER) XDEBUG_MODE=coverage $(PHP) -dxdebug.mode=coverage $(PHPUNIT) --testsuite "all" --coverage-html ${COVERAGE_DIR}

## ---- Tools ---------------------------------------------------------------------
tools-install: psalm-install ## Install Tools
	mkdir -vp ${PHP_CS_FIXER_DIR}
	$(COMPOSER) require --working-dir=${PHP_CS_FIXER_DIR} "friendsofphp/php-cs-fixer" --no-interaction --prefer-dist --optimize-autoloader

tools-upgrade: ## Upgrade Tools
	$(COMPOSER) upgrade --working-dir=${PSALM_DIR} --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies
	$(COMPOSER) upgrade --working-dir=${PHP_CS_FIXER_DIR} --no-interaction --prefer-dist --optimize-autoloader --with-all-dependencies

psalm-install:
	mkdir -vp ${PSALM_DIR}
	$(COMPOSER) require --working-dir=${PSALM_DIR} "vimeo/psalm" "psalm/plugin-phpunit" "psalm/plugin-symfony" --no-interaction --prefer-dist --optimize-autoloader
	$(PSALM_PLUGIN) enable psalm/plugin-phpunit || true
	$(PSALM_PLUGIN) enable psalm/plugin-symfony || true
	$(PSALM) --init

psalm: ## Run psalm
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM)

psalm-github: ## Run psalm with GitHub output
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PSALM) --long-progress --monochrome --output-format=github --report=results.sarif

php-cs-fixer: ## Run php-cs-fixer
	XDEBUG_MODE=off $(PHP) -dxdebug.mode=off $(PHP_CS_FIXER) fix -vv --diff --allow-risky=yes --config=.php-cs-fixer.dist.php
