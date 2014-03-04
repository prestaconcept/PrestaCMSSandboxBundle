cs:
	phpcs --extensions=php --encoding=utf-8 --standard=PSR2 -np .

cs-fixer:
	php-cs-fixer fix .
