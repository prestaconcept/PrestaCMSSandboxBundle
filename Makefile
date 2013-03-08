install:
	app/console doctrine:database:drop --force
	app/console doctrine:database:create
	app/console doctrine:schema:create
	chmod 777 app/database app/logs app/cache
	chmod 777 app/database/*
	chmod -R 777 web/uploads
	app/console doctrine:phpcr:init:dbal
	app/console doctrine:phpcr:register-system-node-types
	app/console doctrine:phpcr:fixtures:load --no-interaction
	app/console doctrine:fixture:load --no-interaction

deploy-configure:
	curl -s http://getcomposer.org/installer | php
	php composer.phar install
	app/console assets:install web
	app/console assetic:dump --env=prod

deploy-install:
	app/console cache:clear --env=prod
	chmod 777 app/database app/logs app/cache
	chmod 777 app/database/*
	chmod -R 777 web/uploads

deploy-update: cc install

cc:
	app/console cache:clear --env=prod

cs:
	phpcs --extensions=php -n --standard=PSR2 --report=full src
