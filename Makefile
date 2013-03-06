install:
	app/console doctrine:database:drop --force
	app/console doctrine:database:create
	app/console doctrine:schema:create
	chmod 777 app/database app/logs app/cache
	chmod 777 app/database/*
	app/console doctrine:phpcr:init:dbal
	app/console doctrine:phpcr:register-system-node-types
	app/console doctrine:phpcr:fixtures:load --no-interaction
