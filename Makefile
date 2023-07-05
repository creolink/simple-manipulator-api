default: help

help:
	@echo ""
	@echo ""
	@echo "Operations:"
	@echo " ----------"
	@echo " prod             Start production application (detached)"
	@echo " up               Start development application"
	@echo " down             Stop application"
	@echo " remove           Stop application and remove images"
	@echo " ----------"
	@echo " nginx            Get into nginx container"
	@echo " php              Get into php container"
	@echo " ----------"
	@echo ""
	@echo ""


prod:
	make remove
	docker compose -f "docker-compose.prod.yml" up -d --build

up:
	docker compose -f "docker-compose.dev.yml" up --build

down:
	docker compose -f "docker-compose.prod.yml" down
	docker compose -f "docker-compose.dev.yml" down

clean-vendor:
	rm -r -f app/vendor/

clean-tmp:
	rm -f app/tmp/*.*

remove:
	make down
	make clean-vendor
	make clean-tmp
	docker rmi anwaltde-nginx -f & docker rmi anwaltde-php -f & docker rmi anwaltde-composer -f

nginx:
	docker exec -it anwaltde-nginx sh

php:
	docker exec -i -t anwaltde-php sh