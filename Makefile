container_name=static-apache

up: 
	docker compose up -d
down: 
	docker compose down
exec: 
	docker exec -it $(container_name) bash
cs-check:
	php vendor/bin/php-cs-fixer check
cs-fix:
	php vendor/bin/php-cs-fixer fix
test:
	php vendor/bin/phpunit tests
document:
	php vendor/bin/openapi src --output swagger.json
