init: up
up:
	docker-compose up -d
goapp:
	docker-compose exec app-php bash
recreate-php:
	docker-compose up -d --force-recreate --no-deps --build app-php