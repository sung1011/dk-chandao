
help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  start				docker-compose up -d"
	@echo "  stop				docker-compose down && make clean"
	@echo "  restart			docker-compose down && make clean"
	@echo "  clean				clean all"
	@echo "  logs				docker-compose logs -f"
	@echo "  redis-dump			redis dump"
	@echo "  redis-restore			redis restore"
	@echo "  mongo-dump			mongo dump"
	@echo "  mongo-restore			mongo restore"
	@echo "  ps				docker-compose ps"
	@echo "  top				docker-compose top"

.PHONY: start
start:
	@sh init
	@docker-compose up -d
	@docker-compose logs -f

.PHONY: stop
stop:
	@docker-compose down -v
	@docker container prune -f
	@docker image prune -f
	@make clean

.PHONY: restart
restart:
	@make stop
	@make start

ps:
	@docker-compose ps -a

top:
	@docker-compose top

.PHONY: clean
clean:
	@rm -Rf data/logs/nginx/*
	@rm -Rf data/logs/phpfpm/*
	@rm -Rf data/logs/mongodb/*
	# @rm -Rf data/db/redis/*
	# @rm -Rf data/db/mongodb/*
	# @rm -Rf data/db/mysql/*

.PHONY: logs
logs:
	@docker-compose logs -f

redis-dump:
	@echo "TODO"
redis-restore:
	@echo "TODO"
mongo-dump:
	@echo "TODO"
mongo-restore:
	@echo "TODO"

# init:
