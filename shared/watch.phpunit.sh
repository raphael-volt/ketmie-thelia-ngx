#!/bin/bash

CONFIG="0"
BOOTSTRAP="0"
FILE="0"
TESTDOX=""
WATCH=/var/www/html
HELP="0"
WATCH_EXISTS=false
WATCH_R=" -r"

while getopts 'c:b:f:w:t:h' option; do
  case "$option" in
    c) 
        CONFIG="$OPTARG"
        ;;
    b) 
        BOOTSTRAP="$OPTARG"
        ;;
    f) 
        FILE="$OPTARG"
        ;;
    w) 
        WATCH="$OPTARG"
        ;;
    h) 
        HELP="1"
        ;;
    t) 
        TESTDOX="--$OPTARG"
        ;;
    ?)
        HELP="1"
        ;;
  esac
done
 
watch() {

    WITH_CONFIG=false
    
    if [ $CONFIG != "0" ]; then
        WITH_CONFIG=true
    fi

    if $WITH_CONFIG; then
        runTestSuite
    else
        runTest
    fi

    inotifywait -m $WATCH$WATCH_R -e close_write -e create -e delete -e move --format '%w %e %T' --timefmt '%H%M%S' | while read file event tm; do
        current=$(date +'%H%M%S')
        delta=`expr $current - $tm`
        if [ $delta -lt 2 -a $delta -gt -2 ] ; then
            sleep 1  # sleep 1 set to let file operations end
            if $WITH_CONFIG; then
                runTestSuite
            else
                runTest
            fi
        fi
    done
}

runTest() {
    phpunit $TESTDOX --bootstrap $BOOTSTRAP $FILE
}

runTestSuite() {
    phpunit $TESTDOX -c $CONFIG
}

printHelp() {
    cat << EOF

Live reload PhpUnit test runner.

usage: watch-phpunit [-c <config> | -b <bootstrap> -f <file>] [-w <dir-or-file-to-watch> default:/var/www/html]
    watch-phpunit -b autoload.php -f specs/tests/MyTest.php
    watch-phpunit -c suite.xml
    watch-phpunit -c suite.xml -w index.php
    watch-phpunit -c suite.xml -w specs/tests

EOF
}

if [ "$FILE" != "0" ] || [ "$BOOTSTRAP" != "0" ]; then
    if [ "$FILE" = "0" ] || [ "$BOOTSTRAP" = "0" ]; then
        echo "missing f|b"
        HELP="1"
    elif [ "$CONFIG" != "0" ]; then
        HELP="1"
    fi
elif [ "$CONFIG" = "0" ]; then
    HELP="1"
fi

if [ "$HELP" = "1" ]; then
    printHelp
    exit
fi

if [ "$FILE" != "0" ] && [ ! -f "$FILE" ]; then
    echo "File '$FILE' not found."
    exit 1
fi

if [ "$CONFIG" != "0" ] && [ ! -f "$CONFIG" ]; then
    echo "File '$CONFIG' not found."
    exit 1
fi

if [ "$BOOTSTRAP" != "0" ] && [ ! -f "$BOOTSTRAP" ]; then
    echo "File '$BOOTSTRAP' not found."
    exit 1
fi

if [ -d $WATCH ]; then
    WATCH_EXISTS=true
fi
if [ -f $WATCH ]; then
    WATCH_R=""
    WATCH_EXISTS=true
fi

if ! $WATCH_EXISTS; then
    echo "Can't watch '$WATCH', file or directory not found."
    exit 1
fi

watch

