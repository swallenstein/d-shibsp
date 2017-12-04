#!/bin/bash

scriptsdir=$(cd $(dirname $BASH_SOURCE[0]) && pwd)
proj_home=$(cd $(dirname $scriptsdir) && pwd)

rm -rf ${proj_home}/work/etc/* 2>/dev/null || true
mkdir -p ${proj_home}/work/etc

cmd="${proj_home}/scripts/express_setup.sh
     -e ${proj_home}/work/etc
     -O ${proj_home}
     -s ${proj_home}/config/express_setup.yaml"

echo $cmd
$cmd