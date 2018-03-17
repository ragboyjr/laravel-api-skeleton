.PHONY: test test-integration keys refresh-db clear-doctrine-cache deploy permissions local-access-token clean-project ignored-files healthcheck

SHELL:=/bin/bash

IGNORED_FILES=docker-compose.override.yml .env etc/mysql/.env playground.php

deploy:
	composer install
	make clear-doctrine-cache
	./artisan doctrine:generate:proxies
	./artisan doctrine:migrations:migrate
	fixtures
	make permissions healthcheck
setup:
	composer install
	make keys
	./artisan key:generate
	make clear-doctrine-cache
	./artisan doctrine:generate:proxies
	make refresh-db permissions local-token

local-token:
	./artisan oauth2:access-token local local123 --scope=basic

permissions:
	chmod -R 777 storage/logs/*
	chmod -R 777 bootstrap/cache

test:
	./vendor/bin/phpunit tests/Unit
test-integration:
	./vendor/bin/phpunit tests/Integration

keys: storage/oauth-private.key

storage/oauth-private.key:
	./artisan oauth2:generate-keys
	chmod 600 storage/oauth*.key
	chown www-data:www-data storage/oauth*.key

refresh-db:
	./artisan doctrine:migrations:refresh --seed
	./artisan db:seed --class=OAuthSeeder

clear-doctrine-cache:
	./artisan doctrine:clear:metadata:cache
	./artisan doctrine:clear:query:cache
	./artisan doctrine:clear:result:cache

ignored-files: $(IGNORED_FILES)

healthcheck:
	echo ref: `git rev-parse --abbrev-ref HEAD` > public/_healthcheck
	echo tag: `git tag --points-at HEAD` >> public/_healthcheck
	echo msg: `git show HEAD --oneline -s` >> public/_healthcheck
	echo date: `date -R` >> public/_healthcheck

$(IGNORED_FILES): %: %.example
	cp $< $@

clean-project:
	rm -rf $(IGNORED_FILES) vendor
