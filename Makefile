ENV = "dev"

clean:
	app/console doctrine:database:drop --force --env=$(ENV)

install:
	app/console doctrine:database:create --env=$(ENV)
	chmod 777 app/database/*
	app/console doctrine:schema:create --env=$(ENV)
	app/console doctrine:phpcr:repository:init --env=$(ENV)
	app/console doctrine:phpcr:fixtures:load --no-interaction --env=$(ENV)
	app/console doctrine:fixture:load --no-interaction --env=$(ENV)
	app/console assetic:dump --env=$(ENV)

refresh:
	app/console doctrine:phpcr:fixtures:load --no-interaction --env=$(ENV)
	app/console doctrine:fixture:load --no-interaction --env=$(ENV)
	app/console cache:clear --env=$(ENV)

pr:
	app/console doctrine:phpcr:fixtures:load --no-interaction

or:
	app/console doctrine:fixtures:load --no-interaction

cc:
	rm -rf app/cache/*

cs:
	phpcs --extensions=php -n --standard=PSR2 --report=full src

ai:
	app/console assets:install web

## Launch this on first deploy
deploy-install: ENV = "prod"
deploy-install: install

## Launch this when you already have an instance
deploy-update: ENV = "prod"
deploy-update: clean
deploy-update: deploy-install

deploy-refresh: ENV = "prod"
deploy-refresh: refresh

test-clean: ENV = "test"
test-clean: clean

test-install: ENV = "test"
test-install: install
