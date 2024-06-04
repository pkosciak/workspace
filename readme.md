## Step 1. Traefik setup

Create network:

```
docker network create proxy
```

In traefik directory run command

```
docker-compose up -d
```


## Step 2. Mailhog setup

In mailhog directory run command

```
docker-compose up -d
```

to see inbox, visit:

```
mailhog.test
```

## Step 3. ElasticSearch setup

In elasticsearch directory run command

```
docker-compose up -d
```

To connect to elastic search use hostname: elasticsearch

## Step 4. Project setup

Clone recipe from recipes directory into projects directory

Change directory name eg. newproject1

Edit .env file to your preferences

Put your application files into src directory

In project directory (not src) run command:
```
docker-compose up -d
```

To create ssl certificate, run on local machine:

```
choco install mkcert
mkcert -install
mkcert -cert-file certs/local-cert.pem -key-file certs/local-key.pem "newproject1.test" "*.newproject1.test"
```

Copy .pem files to directory traefik/certs

Recreate traefik container


## Recreate container

In case you need to make changes in containers, you can run this command afterwards:

```
docker-compose up -d --build --force-recreate
```

## New project
generate new .pem files including your new project name and clone them into traefik/certs - do this for each new project
command will now look like this:
```
mkcert -cert-file certs/local-cert.pem -key-file certs/local-key.pem^
 "newproject1.test" "*.newproject1.test"^
 "newproject2.test" "*.newproject2.test"^
```

add hostname to C:\Windows\System32\drivers\etc\hosts file
127.0.0.1 newproject1.test
127.0.0.1 newproject2.test

## Mysql data storage

Mysql data is stored in .docker/mysql/data, so it won't be affected as data is not stored inside volume. Ir can be lost if your WSL instance breaks, just in case back up data once in a while.

To connect to mysql container use mysql57 or mysql8 as a hostname

You can also set up each project to use its own mysql instance if you need specific configuration for it, just uncomment container setup inside doccker-compose file, remember to apply unique port number

## Executing commands in project (e.g. composer install)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-php bash
```
```
composer install
```

## WPCLI (wordpress recipe)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-cli bash
```
```
wp help
```