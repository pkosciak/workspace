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

Change directory name e.g. newproject1

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

Add hostname to C:\Windows\System32\drivers\etc\hosts file
127.0.0.1 newproject1.test


## Recreate container

In case you need to make changes in containers, you can run this command afterward:

```
docker-compose up -d --build --force-recreate
```

## New project
Repeat step 4, including these changes:
Generate new .pem files including your new project name and clone them into traefik/certs - do this for each new project
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

Mysql data is stored in newporject1/.docker/mysql/data. It can be lost if your WSL instance breaks, just in case back up data once in a while.

To connect to mysql container use mysql57 or mysql8 as a hostname

You can also set up each project to use its own mysql instance if you need specific configuration for it, just uncomment container setup inside docker-compose file, remember to apply unique port number

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