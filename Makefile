.DEFAULT_GOAL := help
.PHONY: help tests

help: ## Display help
	@echo "\033[33mAPI commands helper\033[0m"
	@echo "============================================"
	@echo ""
	@echo "This Makefile provides helpful commands to build, test, and manage the project and its environment."
	@echo "Feel free to explore the available targets and adjust them if needed."
	@echo ""
	@echo "\033[33mUsage:\033[0m make [option]"
	@echo ""
	@awk ' \
	BEGIN { \
		printf("\033[32m%-30s\033[0m %s\n", "Target", "Description"); \
		print "------------------------------------------\n"; \
	} \
	/^[#][#][ ]*[a-zA-Z]/ { \
		gsub(/^## /, ""); \
		print "\n\033[33m" $$0 "\033[0m"; \
		print "\033[33m------------------------------------------\033[0m"; \
	} \
	/^([a-zA-Z_-]+:([^=]|$$))/ { \
		command = substr($$1, 1, index($$1, ":") - 1); \
		description = substr($$0, index($$0, "##") + 3); \
		printf("\033[32m%-30s\033[0m %s\n", command, description); \
	} \
	' $(MAKEFILE_LIST)
	@echo ""


# Configuration and variables
# ----------------------------------------------------------------------------
CONTAINER = docker exec -it pdv-php


## System commands
# ----------------------------------------------------------------------------
clear: ## Clear the cache
	@$(CONTAINER) bin/console cache:clear

dependencies: ## Install dependencies the API project (How: make dependencies)
	@$(CONTAINER) composer install

enter: ## Enter into the PHP container (How: make enter)
	@$(CONTAINER) bash

owner: ## Set current system used to be the owner of project files (How: make owner)
	@sudo chown -R $$USER:$$USER ./


## Database
# ----------------------------------------------------------------------------
db-create: ## (Re)create the whole database (How: make db-create)
	@$(CONTAINER) bin/console doctrine:database:drop --force
	@$(CONTAINER) bin/console doctrine:database:create

db-migrate: ## Apply migrations on database schema changes (How: make db-migrate)
	@$(CONTAINER) bin/console doctrine:migration:migrate

db-reset: ## Apply migrations on database schema changes (How: make db-migrate)
	@$(CONTAINER) bin/console doctrine:database:drop --force
	@$(CONTAINER) bin/console doctrine:database:create
	@docker exec pdv-php bin/console doctrine:migration:migrate


## Datasets
# ----------------------------------------------------------------------------
datasets: ## Purge database and load the datasets (How: make datasets)
	@$(CONTAINER) sh -c "bin/console doctrine:fixtures:load --no-interaction"


## Testing operations
# ----------------------------------------------------------------------------
tests: ## Run all tests (How: make tests)
	@$(CONTAINER) sh -c "php vendor/bin/phpunit"

tests-filter: ## Run test for a specific class, set of classes or from a path (How: make tests-filter input=<TestClassName>)
	@$(CONTAINER) sh -c "php vendor/bin/phpunit --filter ${input}"

tests-coverage: ## Run all tests and display coverage (How: make tests-coverage)
	@$(CONTAINER) sh -c "php vendor/bin/phpunit --coverage-text"

tests-report: ## Run all tests and generate a report of coverage in "var/reports/code-coverage" directory (How: make tests-report)
	@$(CONTAINER) sh -c "php vendor/bin/phpunit --coverage-html='var/reports/code-coverage'"
