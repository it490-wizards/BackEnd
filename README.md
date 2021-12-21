# BackEnd

Install the PHP dependencies using [Composer](https://getcomposer.org/).

```sh
composer install
```

Create a file `config.ini` with your MySQL and RabbitMQ credentials.

```ini
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
vhost = "database"
```

Run `create_tables.php` and finally run `database_server.php`.
