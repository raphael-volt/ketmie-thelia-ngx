# Ketmie e-shop



## Docker

The `xdebug.remote_host` must be set for debug:

- with `php.ini`
    ```ini
    xdebug.remote_host=<HOST_IP>
    ```
- with `docker-compose`
    ```yml
    environment:
        XDEBUG_CONFIG: remote_host=<HOST_IP>
    ```

```bash
# Build containers
docker-compose build
# Build containers then run them
docker-compose up --build
# Run containers
docker-compose up
```

# Eclipse PDT configuration #

## xdebug ##

![alt text](doc/eclipse-xdebug-config/img/php.debuggers.jpg)

### Edit configuration ###

![alt text](doc/eclipse-xdebug-config/img/php.xdebug.jpg)

## Servers ##

![alt text](doc/eclipse-xdebug-config/img/php.servers.jpg)

### Create or edit a server ###

![alt text](doc/eclipse-xdebug-config/img/php.server.jpg)

### Configure debugger ###

![alt text](doc/eclipse-xdebug-config/img/php.debugger.jpg)

### Configure path mapping ###

![alt text](doc/eclipse-xdebug-config/img/php.pathmapping.jpg)

## Debbug configurations

![alt text](doc/eclipse-xdebug-config/img/debug.config.jpg)

### Create a new `PHP Web Application` launch configuration
![alt text](doc/eclipse-xdebug-config/img/debug.server.jpg)

### Disable `Break at first line` option
![alt text](doc/eclipse-xdebug-config/img/debug.debugger.jpg)

# PHPUnit

Launch debugger from eclipse.

Open a terminal in the `ketmie_php7` container:

```bash
docker exec -it ketmie_php7 bash
```

Run PHPUnit:
```bash
# Single test
phpunit --bootstrap specs/autoload.php specs/tests/PDOTest.php
# Test suite
phpunit -c specs/suite.xml
# Live test suite reload
cd /shared/
./phpunit.watch.sh
```

## Usefull commands

Get host IP address

```bash
hostname -I | awk '{print $1}'
```

Stop all running containers :

```bash
docker stop $(docker ps -a -q)
```

Open a terminal in a container

```bash
docker exec -it <container-name|container_ID> bash
```
