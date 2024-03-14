## Generating certs

Inside traefik/certs:

```
mkcert -install
mkcert -cert-file certs/local-cert.pem -key-file certs/local-key.pem "docker.localhost" "*.docker.localhost"
```

On local machine:

```
choco install mkcert
mkcert -install
mkcert "docker.localhost" "*.docker.localhost"
```

Important: You may need to restart chrome to see your project as secure

## Traefik setup

Create network:

```
docker network create proxy
```

In traefik directory run command

```
docker-compose up -d
```

to see traefik dashboard, visit:

```
traefik.docker.localhost
```

## Mailhog setup

In mailhog directory run command

```
docker-compose up -d
```

to see inbox, visit:

```
mailhog.docker.localhost
```

## ElasticSearch setup

In elasticsearch directory run command

```
docker-compose up -d
```

To connect to elastic search use hostname: elasticsearch

## Project setup

Clone recipe from recipes directory into projects directory

Change directory name

Edit .env file to your preferences

Put your application files into src directory

In project directory (not src) run command:
```
docker-compose up -d
```

## Recreate project

In case you need to change Dockerfile inside your project run command:

```
docker-compose up -d --build --force-recreate
```

Mysql data is stored in .docker/mysql/data, so it won't be affected as data is not stored inside volume. Ir can be lost if your WSL instance breaks, just in case back up data once in a while.

## Executing commands in project (e.g. composer install)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-php bash
```

## WPCLI (wordpress recipe)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-cli wp help
```
