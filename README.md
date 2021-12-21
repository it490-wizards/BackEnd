# BackEnd

Install the PHP dependencies using [Composer](https://getcomposer.org/).

```sh
composer install
```

Create a file `config.ini` with your MySQL and RabbitMQ credentials. You should have separate virtual hosts for communication to the frontend and to the DMZ.

```ini
[api-proxy]
host = "localhost"
port = 5672
user = "username"
password = "password"
vhost = "api-proxy"

[mysql]
hostname = "localhost"
username = "username"
password = "password"
database = "whatever"

[rabbitmq]
host = "localhost"
port = 5672
user = "username"
password = "password"
vhost = "frontend"
```

Run `create_tables.php` and finally run `database_server.php`.
