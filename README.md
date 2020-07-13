

## Установка
```bash
docker-compose build

docker-compose up -d

docker exec -it php7.4-fpm bash -c "cd ./bgsgroup/app/ && php -r \"copy('https://getcomposer.org/installer\', 'composer-setup.php');\" && php composer-setup.php"

docker exec -it php7.4-fpm php ./bgsgroup/app/composer.phar install

docker exec -it php7.4-fpm php ./bgsgroup/app/composer.phar dump-autoload

docker exec -it php7.4-fpm php ./bgsgroup/app/artisan db:create

docker exec -it php7.4-fpm php ./bgsgroup/app/artisan migrate

docker exec -it php7.4-fpm php ./bgsgroup/app/artisan db:seed

```

Добавить в hosts запись

```
127.0.0.1 bgsgroup
```

В браузере указать 

```
http://bgsgroup
```

По адресу http://localhost расположен перечень ссылок на phpinfo и phpmyadmin. 

#### Тесты

Очень важно, чтобы контекст команды оставался такой как есть. 
В противном случае возникнет проблема при выполнении команды очередей. 
Это следствие потери путей до файла конфигурации .env 

```
docker exec -it php7.4-fpm bash -c "cd bgsgroup/app/app && ../vendor/bin/phpunit ../tests/CustomTest"
```

### Порядок работы

На этапе развертывания системы в базе уже существует справочник городов, мероприятий 
и дефолтный пользователь, из-под которого будут выполняться все запросы к API.

Токен: 
```
6cdeaf954812376a1db7434a4ed53549
```

Для наглядности здесь будут консольные команды cURL, хотя сам использовал для отладки Postman.

#### Api key

Перед любым запросом к API, необходимо получить ключ api_key.  

```
curl --location --request GET 'http://bgsgroup/access-api-key/6cdeaf954812376a1db7434a4ed53549'
```

Результатом будет ответ json

```
{
    "http_code": 200,
    "error": [],
    "data": {
        "api_key": "$2y$10$kxMei8QkP7jKSIQ37R9NReSAq1KktFDf340e4Py..Us2tjKX6fVce",
        "expired": "20200713182925"
    }
}
```

Время жизни ключа один час.

#### Добавление участника

Добавление участника в мероприятие. Если участника физически еще нет в системе, 
то он создается.

```
curl --location --request POST 'http://bgsgroup/api/events/4/user/create' \
--header 'x-api-key: {your_api_key}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'name=пользователь ' \
--data-urlencode 'surname=Тестовый' \
--data-urlencode 'email={your_email}'
```

Атрибуты {your_api_key} и {your_email} нужно указать самим.

Каждое новое добавление участика в мероприятие сопровождается добавлением 
в очередь email рассылки. Сама отправка почты не реализована. 
Очередь складывается в БД таблицы jobs.

#### Удаление участника

Удаление участника из мероприятия.

```bash
curl --location --request DELETE 'http://bgsgroup/api/events/1/user/3/delete' \
--header 'x-api-key: {your_api_key}'
```

Атрибут {your_api_key} нужно указать самим.

#### Изменение

Изменение мероприятия у участника.

```bash
curl --location --request POST 'http://bgsgroup/api/events/1/user/1/update' \
--header 'x-api-key: {your_api_key}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'event_id=3'
```

Атрибут {your_api_key} нужно указать самим.

#### Участники мероприятия

Получение всех участников по определенному мероприятию

```bash
curl --location --request GET 'http://bgsgroup/api/events/4/user/list' \
--header 'x-api-key: {your_api_key}'
```

Атрибут {your_api_key} нужно указать самим.

