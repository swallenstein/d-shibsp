#!/bin/bash

function main {
    # transition from root to daemon user is handled by shibd/httpd; must start as root
    if [ $(id -u) -ne 0 ]; then
        echo "must start shibd and httpd as root"
        exit 1
    fi
    cleanup_and_prep
    start_shibd
    start_httpd
}


function cleanup_and_prep {

    # correct ownership (docker run will reset the ownership of volumes at creation time).
    # Only a problem with /etc/shibboleth, where mod_shib needs to have access with the httpd id

    # Make sure we're not confused by old, incompletely-shutdown shibd or httpd
    # context after restarting the container. httpd/shibd won't start correctly if thinking it is already running.
    rm -rf /var/lock/subsys/shibd
    su - $SHIBDUSER  -c '[ -e /run/shibboleth/shibd.sock ] && rm /run/shibboleth/shibd.*'
}


function start_shibd {
    echo "starting shibd" > /var/log/startup/start.log 2>&1
    export LD_LIBRARY_PATH=/opt/shibboleth/lib64
    /usr/sbin/shibd -u $SHIBDUSER -g root -p /var/run/shibboleth/shib.pid >> /var/log/startup/start.log 2>&1
}


function start_httpd {
    echo "starting httpd" >> /var/log/startup/start.log 2>&1
    # `docker run` 1.12.6 will reset ownership and permissions on /run/httpd; therefore it need to be done again:
    # do not start with root to avoid permission conflicts on log files
    su - $HTTPDUSER  -c 'rm -f /run/httpd/*' >> /var/log/startup/start.log 2>&1
    su - $HTTPDUSER  -c 'httpd -DFOREGROUND -d /etc/httpd/ -f conf/httpd.conf'  >> /var/log/startup/start.log 2>&1
    su - $HTTPDUSER  -c 'echo "httpd started with pid=$(cat /run/httpd/httpd.pid)"' >> /var/log/startup/start.log 2>&1
}


main
