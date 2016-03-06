#!/bin/bash
set -e   # exit if a command fails

# transition from root to daemon user is handled by shibd/httpd

/etc/shibboleth/shibd-redhat start

# Apache gets grumpy about PID files pre-existing
rm -f /var/logs/httpd.pid
httpd -DFOREGROUND
