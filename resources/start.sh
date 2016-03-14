#!/bin/bash
set -e   # exit if a command fails

# transition from root to daemon user is handled by shibd/httpd; must start as root
if [ $(id -u) -ne 0 ]; then
    echo "must start shibd and httpd as root"
    exit 1
fi

/etc/shibboleth/shibd-redhat start

# Apache gets grumpy about PID files pre-existing
rm -f /var/logs/httpd.pid
httpd -DFOREGROUND
