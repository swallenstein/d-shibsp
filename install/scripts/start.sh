#!/bin/bash
set -e   # exit if a command fails

# transition from root to daemon user is handled by shibd/httpd; must start as root
if [ $(id -u) -ne 0 ]; then
    echo "must start shibd and httpd as root"
    exit 1
fi

#SHIBSP_PREFIX=/opt/etc/shibboleth
SHIBSP_PREFIX=/etc/shibboleth
$SHIBSP_PREFIX/shibd-redhat start

# Apache dislikes pre-existing PID files
rm -f /var/logs/httpd.pid
httpd -DFOREGROUND -d /opt/etc/httpd/ -f conf/httpd.conf
