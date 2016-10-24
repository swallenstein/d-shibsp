#!/bin/bash
set -e   # exit if a command fails

# transition from root to daemon user is handled by shibd/httpd; must start as root
if [ $(id -u) -ne 0 ]; then
    echo "must start shibd and httpd as root"
    exit 1
fi


function cleanup {
    # Make sure we're not confused by old, incompletely-shutdown shibd or httpd
    # context after restarting the container. httpd and shibd won't start correctly                                                                                                                                                        # if thinking it is already running.
    rm -f /var/lock/subsys/shibd
    rm -f /var/run/shibboleth/shibd.*
    rm -rf /run/httpd/*
}

cleanup

/etc/shibboleth/shibd-redhat start 2>&1 > /var/log/start.log

echo "starting httpd" >> /var/log/start.log
httpd -DFOREGROUND -d /opt/etc/httpd/ -f conf/httpd.conf