# Ketmie e-shop



## Docker

```bash
# Build containers
docker-compose build
# Build containers then run them
docker-compose up --build
# Run containers
docker-compose up
```

### Usefull commands

Stop all running containers :

```bash
docker stop $(docker ps -a -q)
```

Open a terminal in a container

```bash
docker exec -it <container-name|container_ID> bash
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

Open a terminal in the container:

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


