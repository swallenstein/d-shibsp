#!/usr/bin/env bash

main() {
    _set_projhome
    _get_commandline_opts $@
    if [[ $keygen ]]; then
        _shibboleth_gen_keys
    fi
    _shibboleth_gen_metadata
    _postprocess_metadata
}


_set_projhome() {
    scriptsdir=$(cd $(dirname $BASH_SOURE[0]) && pwd)
    proj_home=$(cd $(dirname $buildscriptsdir) && pwd)
}


_get_commandline_opts() {
    while getopts ":ko:" opt; do
      metadata_edited='sp_metadata.xml'
      case $opt in
        k) keygen='True';;
        o) metadata_edited=$OPTARG;;
        :) echo "Option -$OPTARG requires an argument"; exit 1;;
        *) echo "usage: $0 [-k] [-o  metadata_output]
           -k  (re)generate SP signature keys
           "; exit 0;;
      esac
    done
    shift $((OPTIND-1))
}


_shibboleth_gen_keys() {
    ${proj_home}/metagen.sh -c sp-cert.pem -h $HOSTNAME -e $ENTITYID \
        > /tmp/sp_metadata_to_be_edited.xml
}


_shibboleth_gen_metadata() {
    # create XSLT for processing SP-generated metadata
    python ${proj_home}/scripts/postprocess_metadata.py \
           ${proj_home}/testdata/postprocess_metadata.yaml \
           ${proj_home}/xslt/postprocess_metadata.template \
           > /tmp/postprocess_metadata.xslt
}


_postprocess_metadata() {
    # create final metadata
    xsltproc /tmp/postprocess_metadata.xslt /tmp/sp_metadata_to_be_edited.xml > $metadata_edited
    echo "generated SP metadata into $metadata_edited"
}


main $@