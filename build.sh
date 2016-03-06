#!/usr/bin/env bash

SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
source $SCRIPTDIR/conf.sh

if [ $(id -u) -ne 0 ]; then
    sudo="sudo"
fi
${sudo} docker rmi -f $IMAGENAME
${sudo} docker build \
    --build-arg "SHIBDUSER=$SHIBDUSER" \
    --build-arg "SHIBDUID=$SHIBDUID" \
    --build-arg "HTTPDUSER=$HTTPDUSER" \
    --build-arg "HTTPDUID=$HTTPDUID" \
    -t=$IMAGENAME .
