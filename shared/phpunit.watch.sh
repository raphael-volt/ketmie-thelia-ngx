#!/bin/sh

phpunit -c /var/www/html/specs/suite.xml

inotifywait -m /var/www/html -r -e close_write -e create -e delete -e move --format '%w %e %T' --timefmt '%H%M%S' | while read file event tm; do
    current=$(date +'%H%M%S')
    delta=`expr $current - $tm`
    if [ $delta -lt 2 -a $delta -gt -2 ] ; then
        sleep 1  # sleep 1 set to let file operations end
        phpunit -c /var/www/html/specs/suite.xml
    fi
done