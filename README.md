# Product overview

## Atte
Time and attendance management system of a company

## Production background and purpose
For personnel evaluation

## Production goals
From a mature evaluation, make an evaluation evaluation.
Increased efficiency.

## Server
Heroku

## language
PHP

## Framework
Laravel

# How to build the environment

## MAMP/XAMPP
Environment construction (Mac)

Install MAMP

https://www.mamp.info/en/downloads/

Environment construction (Windows)

Install XAMPP

https://www.apachefriends.org/jp/index.html

## clone
git clone https://github.com/yomogi-tyannneru/ADVANCE.git

## 　Email authentication

```php
MAIL_DRIVER = smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT = 587
MAIL_USERNAME = own gmail
MAIL_PASSWORD = own app password
MAIL_ENCRYPTION = tls
MAIL_FROM_ADDRESS = own gmail
MAIL_FROM_NAME = any app name
```

Change .env file to above

○ Reference site

[https://zenn.dev/kazushino/articles/67da2015865ae117444c](https://zenn.dev/kazushino/articles/67da2015865ae117444c)

## Test
Create tests using PHPUnit

vendor/bin/phpunit is the test code.

vendor/bin/phpunit tests/Feature/Auth/AuthenticationTest.phpis a certification-only test.

## Docker
Build an environment using Docker

Docker installation (Mac)

https://www.docker.com/products/docker-desktop

Docker installation (Windows)

https://hub.docker.com/editions/community/docker-ce-desktop-windows/


## Isolation of the environment
Separate the development environment from the production environment

## Heroku
https://lit-peak-96335.herokuapp.com/








