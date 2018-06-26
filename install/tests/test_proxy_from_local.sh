#!/bin/bash

host='edupay-qs.bildung.at'
if [[ "$1" ]]; then
	path=$1
else
	path='/portal'
fi

curl -v \
    -H "host:$host" \
    -H "X-Forwarded-Host:$host" \
    -H 'X-Forwarded-Proto:https' \
    http://localhost:8080/$path