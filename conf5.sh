#!/usr/bin/env bash

# data shared between containers goes via these definitions:
dockervol_root='/docker_volumes'
shareddata_root="${dockervol_root}/1shared_data"

# configure container
export IMGID='5'  # range from 1 .. 99; must be unique
export IMAGENAME="r2h2/shib${IMGID}"
export CONTAINERNAME="${IMGID}sp1TestWpv"
export CONTAINERUSER='0'  # run container with root, transition to non-root handled by daemons
export SHIBDUSER="shibd${IMGID}"
export SHIBDUID="800${IMGID}"
export HTTPDUSER="httpd${IMGID}"
export HTTPDUID="900${IMGID}"
export BUILDARGS="
    --build-arg "SHIBDUSER=$SHIBDUSER" \
    --build-arg "SHIBDUID=$SHIBDUID" \
    --build-arg "HTTPDUSER=$HTTPDUSER" \
    --build-arg "HTTPDUID=$HTTPDUID" \
"
export ENVSETTINGS="
"
export NETWORKSETTINGS="
    --net http_proxy
    --ip 10.1.1.${IMGID}
"
export VOLROOT="$dockervol_root/$CONTAINERNAME"  # container volumes on docker host
# mounting var/lock/.., var/run to get around permission problems when starting non-root
export VOLMAPPING="
    -v $VOLROOT/etc/pki:/etc/pki:Z
    -v $VOLROOT/etc/httpd:/etc/httpd:Z
    -v $VOLROOT/etc/shibboleth:/etc/shibboleth:Z
    -v $VOLROOT/var/lock/shibboleth:/var/lock/shibboleth:Z
    -v $VOLROOT/var/lock/subsys:/var/lock/subsys:Z
    -v $VOLROOT/var/log/:/var/log:Z
    -v $VOLROOT/var/www/sp1TestWpvPortalverbundAt:/var/www/sp1TestWpvPortalverbundAt:Z
    -v $shareddata_root/md_feed:/opt/md_feed:ro
"
export STARTCMD='/start.sh' # run1.sh starts the container with shibd

# first start: create user/group/host directories
if ! id -u $SHIBDUSER &>/dev/null; then
    groupadd -g $SHIBDUID $SHIBDUSER
    adduser -M -g $SHIBDUID -u $SHIBDUID $SHIBDUSER
fi
if ! id -u $HTTPDUSER &>/dev/null; then
    groupadd -g $HTTPDUID $HTTPDUSER
    adduser -M -g $HTTPDUID -u $HTTPDUID $HTTPDUSER
fi
# create dir with given user if not existing, relative to $HOSTVOLROOT
function chkdir {
    dir=$1
    user=$2
    mkdir -p "$VOLROOT/$dir"
    chown -R $user:$user "$VOLROOT/$dir"
}
chkdir etc/pki $HTTPDUSER
chkdir etc/httpd $HTTPDUSER
chkdir etc/shibboleth $SHIBDUSER
chkdir var/log/httpd $HTTPDUSER
chkdir var/lock/shibboleth $SHIBDUSER
chkdir var/lock/subsys $SHIBDUSER
chkdir var/log/shibboleth $SHIBDUSER
rm -f $VOLROOT/var/run/shibboleth/shibd.sock
chkdir var/www/sp1TestWpvPortalverbundAt/html
