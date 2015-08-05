#WordPress by Maestrano
This version of WordPress is customized to provide Single Sign-On and Star!™ tutorials.
By default, these options are not enabled so an instance of the application can be launched in a Docker container and be run as is.
More information on [Maestrano SSO](https://maestrano.com) and [Star!™ tutorials](https://maestrano.com/star)

## Build Docker container with default WordPress installation
`sudo docker build -t .`

## Activate Maestrano customisation on start (SSO)
This is achieved by specifying Maestrano environment variables

```bash
docker run -it \
  -e "MNO_SSO_ENABLED=true" \
  -e "MNO_MAESTRANO_ENVIRONMENT=local" \
  -e "MNO_SERVER_HOSTNAME=wordpress.app.dev.maestrano.io" \
  -e "MNO_APPLICATION_VERSION=mno-develop" \
  -e "MNO_POWER_UNITS=4" \
  --add-host application.maestrano.io:172.17.42.1 \
  maestrano/wordpress:latest
 ```

## Docker Hub
The image can be pulled down from [Docker Hub](https://registry.hub.docker.com/u/maestrano/wordpress/)
**maestrano/wordpress:stable**: Production version

**maestrano/wordpress:latest**: Development version
