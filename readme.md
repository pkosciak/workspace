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

## Traefik setup

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

In mailhog directory run command

```
docker-compose up -d
```

to see inbox, visit:

```
mailhog.docker.localhost
```

## Project setup

Clone recipe

Edit .env file

Run command:
```
docker-compose up -d
```

## Recreate project

In case you need to change Dockerfile inside your project just run command:

```
docker-compose up -d --build --force-recreate
```

Mysql data is stored in .docker/mysql, so it won't be affected as data is not stored inside volume.

## Executing commands in project (e.g. composer install)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-php bash
```

To exit use
```
exit
```

## WPCLI (wordpress recipe)

Replace APP_NAME with your app name

```
docker exec -it APP_NAME-cli wp help
```

## Important

Remember to back up your mysql data once in a while (in case your WSL instance malfunctions)