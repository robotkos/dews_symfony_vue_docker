
# Quick start

```bash
$ docker-compose up -d
$ docker-compose exec app bash # executing bash inside app service
$ composer install
$ yarn install
$ yarn dev
$ php bin/console doctrine:migration:migrate
$ php bin/console doctrine:fixtures:load
```

Update your `/etc/hosts` file with:

```
127.0.0.1   app.local
127.0.0.1   phpmyadmin.app.local
```

You may now go to [http://app.local/](http://app.local/) and
login using the following credentials:

Login: `Foo`
Password: `bar`
