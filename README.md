
### Развертывание

Установить докер
```
sudo apt install docker
sudo apt install docker-compose
```

Клонировать проект
```
git clone https://github.com/LoidApv/laravelCalc.git
```

Перейти в директорию проекта

Сменить ветку
```
git checkout sail
```

Переименовать файл окружения
```
cp .env.dist .env
```

Запустить докер
```
sudo docker-compose up
```

Перекреститься, чтобы все работало

В отдельном окне консоли подключиться к контейнеру
```
sudo docker-compose exec laravel.test /bin/bash
```

Внутри контейнера накатить миграции
```
php artisan migrate
```

### Запуск и тестирование

Запустить докер
```
sudo docker-compose up
```

Открыть в браузере страницу http://0.0.0.0:81/api/calculation

Открыть инструменты разработчика браузера (F12) и перейти во вкладку Network

На странице формы для ввода значений и кнопки вызова апи

### Документация и автотесты отсутствуют

### P.s. что смог...
