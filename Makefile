.PHONY: help build up down shell install test phpstan

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker image
	docker-compose build

up: ## Start containers
	docker-compose up -d

down: ## Stop containers
	docker-compose down

shell: ## Access PHP container shell
	docker-compose exec php bash

install: ## Install composer dependencies
	docker-compose run --rm php composer install

update: ## Update composer dependencies
	docker-compose run --rm php composer update

test: ## Run PHPUnit tests
	docker-compose run --rm php vendor/bin/phpunit

phpstan: ## Run PHPStan on src
	docker-compose run --rm php vendor/bin/phpstan analyse src --level=max

phpstan-test: ## Run PHPStan on tests
	docker-compose run --rm php vendor/bin/phpstan analyse tests --level=max
