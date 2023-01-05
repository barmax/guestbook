SHELL := /bin/bash

.PHONY: tests

tests:
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test || true
	symfony console doctrine:migrations:migrate -n --env=test || true
	symfony console doctrine:fixtures:load -n --env=test || true
	symfony php bin/phpunit $@ --testdox

