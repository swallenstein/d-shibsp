#!/bin/bash

scriptsdir=$(cd $(dirname $BASH_SOURE[0]) && pwd)
proj_home=$(cd $(dirname $scriptsdir) && pwd)

python ${proj_home}/opt/bin/render_template.py \
       ${proj_home}/config/express_setup.yaml \
       ${proj_home}/xslt/postprocess_metadata.template \
       'Metadata'
