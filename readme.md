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
