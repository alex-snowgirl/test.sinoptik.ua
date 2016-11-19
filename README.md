Установка
=========

Запуска проекта вы можете воспользоваться программой docker и docker-compose

Запустить окружение из корня проекта командой:

```ssh
docker-compose -f ./environment/docker/docker-compose.yml up
```

Или поднять свое окружение.

Ограничений на установку ПО нет.

Инициализация таблиц:
```ssh
./yii migrate
```

Для запуска обновления курсов:
```
./yii update http://bpteam.net/currency.zip
```