# Planoo

This is the main project of Planoo Client Area

## TODO

* create mock data.sql

## What's inside

Customer space with his projects and business plans
Admin space
Partner space

## Project envrionement

### Run

* Linux Ubuntu Server 20.04
* Apache 2.4

### Languages

* PHP
* Twig 1.0.x
* HTML
* SCSS
* jQuery

### Frameworks

* Symfony 3.4 (PHP)
* Doctrine 2.5 (ORM)
* CSS Library
* Materializecss 1.0.0 (Frontend)
* AdminLTE theme (Backend)

### Tools

* PhantomJS 2.1.1 (Generate PDF from HTML/CSS)
* Encore (asset management)

## Setup Project for development

### Access required

#### Bitbucket (git)

A ssh key must have been sent to you

You are allowed to :

* create branches
* push to new branches

You are not allowed to :

* push to develop
* push to master

#### Local DB (via phpmyadmin)

Once installed, local DB can be managed throught phpmyadmin : <http://planoo.local:8081/>

| Login  | Password             |
|---|---|
| root   | planoo_root_password |
| planoo | planoo_password      |

#### Local Planoo website

| Partner     | URL                                           |
|---|---|
| -           | <http://planoo.local/bo/app_dev.php>            |
| Legalstart  | <http://legalstart.planoo.local/bo/app_dev.php> |

### Host OS requirements

#### OS : Linux

Development environment has been tested under Ubuntu 20.04

> :warning: **WSL (Windows Subsystem for Linux) is not recommended**: there is a know issue between xDebug and Docker ==> ports are not forwarded from WSL2 to Windows host

#### Ports

|Port|Usage|Configuration|
|---|---|---|
|80| Apache for Planoo website | docker-compose.yml|
|3306| Mysql DB | docket-compose.yml|
|9005| xDebug | conf/dev/php/php.ini|

> :warning: **if one of them is not available** : you have to update configuration

#### Required tools

|Tool|Usage|Install guide|
|---|---|---|
|git|Source control| sudo apt-get install -y git |
|docker|Virtualization| [Install guide](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository)|
|docker-compose|Virtualization| [Install guide](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository)|
|php-cli|xDebug|sudo apt-get install -y php-cli|

#### Host

Following hosts have to be added to local hosts file (/etc/hosts)

* 127.0.0.1 planoo.local
* 127.0.0.1 legalstart.planoo.local

#### IDE

VSCode is recommended because debug configuration is set and the project is know to work properly with tools but you can use your favorite IDE

##### VSCode Extensions

* Docker by Microsoft
* GitLens by Eric Amodio
* HTML CSS Support by ecmel
* PHP Debug by Felix Becker
* PHP IntelliSense by Felix Becker
* Twig 1 by whatwedo

## Install project for development

### 1 - Download sources from git

```bash
git clone git@factory.planoo.dev:7999/iwa/izypitch.git -b develop planoo
```

### 2 - Run docker

```bash
docker-compose up --build -d
```

### 3 - Install symfony project

```bash
docker-compose exec -u www-data planoo export SYMFONY_ENV=dev
docker-compose exec -u www-data planoo composer install
docker-compose exec -u www-data planoo yarn install
```

### 4 - Import database

Import example throught phpMyAdmin

## Development tools and rules

### JS/CSS

Encore is used to build js ans css assets.

To be able to watch modifications intantly, you must run :

```bash
yarn encore dev --watch &
```

### Database

phpMyAdmin is automaticaly deployed to let you manage DB but data or model edition have to be done as follow

#### DB edition

##### Data

* 1- Generate blank doctrine migration class (data edition)

 ```bash
docker-compose exec -u www-data php bin/console doctrine:migrations:generate
```

* 2- Apply modifications to database

```bash
docker-compose exec -u www-data php bin/console doctrine:migrations:migrate
```

##### Model

* 1- Create/Edit Entities (model edition)
* 2- Generate doctrine migration class

```bash
docker-compose exec -u www-data php bin/console doctrine:migrations:diff
```

* 3- Apply modifications to database

```bash
docker-compose exec -u www-data php bin/console doctrine:migrations:migrate
```

> :warning: **Mock data**: Mock data must be updated according modifications

### Code versionning

Your code has to be pushed on a feature branch whose name has to match the following pattern :

```bash
feature/<your-name-or-pseudo>[-<feature-short-description>]
```

When done, you create a merge request on bitbucket.
Merge is done by admin after code review

### Testing

#### Automated tests

Automated tests are stored in **tests/Selenium IDE/planoo.side**
Tests must be updated if needed
Tests can be lauch locally using "Selenium IDE" Chrome extension

#### Paiement test

Development environement is automatically pluged to Stripe test environement. To fulfill a payment you can use following details :

* Credit card number: 4242 4242 4242 4242
* CVC : 888
* Expiration Date : 12/50

## Troubleshooting

Reporting issues : <franck@planoo.ai>

### I'm redirected to <https://planoo.ai/bo/> after login

Fix BDD for dev (after a BDD import)

```bash
docker-compose exec db mysql -uroot -pplanoo_root_password -e "use planoo; update partners_partner SET customDomain = REPLACE(customDomain, 'planoo.ai', 'planoo.local');"
```

### phantomjs does not install

In phantomjs does not install, try connect to planoo docker

```bash
docker-compose exec planoo bash
export OPENSSL_CONF=/etc/ssl/
php composer.phar install
```
