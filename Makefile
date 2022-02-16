up: docker-up
down: docker-down
init: docker-clear docker-build docker-up app-init

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose pull
	docker-compose build

app-init: app-composer-install app-migrations

app-composer-install:
	docker-compose run --rm php-cli composer install

app-migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction

app-add-tag:
	docker-compose run --rm php-cli php bin/console tag:add

app-update-tag:
	docker-compose run --rm php-cli php bin/console tag:update

app-remove-tag:
	docker-compose run --rm php-cli php bin/console tag:remove

app-search-tag:
	docker-compose run --rm php-cli php bin/console tag:search
