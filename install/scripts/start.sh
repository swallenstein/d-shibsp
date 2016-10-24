#!/bin/bash
set -e   # exit if a command fails

# transition from root to daemon user is handled by shibd/httpd; must start as root
if [ $(id -u) -ne 0 ]; then
    echo "must start shibd and httpd as root"
    exit 1
fi


# Make sure we're not confused by old, incompletely-shutdown shibd or httpd
# context after restarting the container. httpd and shibd won't start correctly
# if thinking it is already running.
SHIBDLOCKFILE=/var/lock/subsys/shibd
HTTPDPIDFILE=/var/run/httpd.pid
rm -f $SHIBDLOCKFILE $HTTPDPIDFILE

#SHIBSP_PREFIX=/opt/etc/shibboleth
SHIBSP_PREFIX=/etc/shibboleth
$SHIBSP_PREFIX/shibd-redhat start

httpd -DFOREGROUND -d /opt/etc/httpd/ -f conf/httpd.conf

exit 0