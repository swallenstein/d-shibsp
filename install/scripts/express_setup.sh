#!/usr/bin/env bash

main() {
    echo ">>running express setup"
    _set_projhome
    _get_commandline_opts $@
    _setup_httpd
    _shibboleth_gen_keys
    _setup_shibboleth2_xml
    _setup_shibboleth2_profile
    _shibboleth_create_metadata_postprocessor
    _postprocess_metadata
    _fix_file_privileges
}


_set_projhome() {
    scriptsdir=$(cd $(dirname $BASH_SOURCE[0]) && pwd)
    proj_home=$(cd $(dirname $scriptsdir) && pwd)
}

_get_commandline_opts() {
    etc_path='/etc'
    opt_path='/opt'
    metadata_edited="$etc_path/shibboleth/export/sp_metadata.xml"
    setupfile=${proj_home}/config/express_setup.yaml
    while getopts ":e:ko:O:s:" opt; do
      case $opt in
        e) etc_path=$OPTARG;;
        k) keygen='True';;
        o) metadata_edited=$OPTARG;;
        O) opt_path=$OPTARG;;
        s) setupfile=$OPTARG;;
        :) echo "Option -$OPTARG requires an argument"; exit 1;;
        *) echo "usage: $0 OPTIONS
           OPTIONS:
           -e  path to shibbleth config (default: /etc; useful for CI-testing to be in the proj_home)
           -k  overwrite existing SP signature keys
           -o  path to post-processed metadata file for federation regsitration (default: $metadata_edited)
           -O  path to opt (default: /opt; useful for CI-testing to be in the proj_home)
           -s  setup file for express configuration (default: $setupfile)
           "; exit 0;;
      esac
    done
    shift $((OPTIND-1))
}


_setup_httpd() {
    hostname=$( ${proj_home}/scripts/get_config_value.py ${proj_home}/config/express_setup.yaml httpd hostname )
    echo ">>generating httpd config for ${hostname}"
    cat /opt/install/etc/hosts.d/testdom.local >> $etc_path/hosts  # FQDNs required for CI-testing
    sed -e "s/^User httpd$/User $HTTPDUSER/" /opt/install/etc/httpd/httpd.conf > $etc_path/httpd/conf/httpd.conf
    sed -e "s/sp.example.org/$hostname/" /opt/install/etc/httpd/conf.d/vhost.conf > $etc_path/httpd/conf.d/vhost.conf
    cp -n /opt/install/etc/httpd/conf.d/* $etc_path/httpd/conf.d/
}


_shibboleth_gen_keys() {
    entityID=$( ${proj_home}/scripts/get_config_value.py ${proj_home}/config/express_setup.yaml Shibboleth2 entityID )
    hostname=$( ${proj_home}/scripts/get_config_value.py ${proj_home}/config/express_setup.yaml httpd hostname )
    echo "generate SP signing key pair and metadata for host=$hostname and entityID=$entityID"
    cd /etc/shibboleth
    if [[ -e sp_cert.pem ]]; then
        if [[ $keygen ]]; then
            ./keygen.sh -f -u $SHIBDUSER -g shibd -y 10 -h $hostname -e $entityID
        else
            echo "using existing signing key (sp_cert.pem). use -k to force re-generationg of key"
        fi
    else
        ./keygen.sh -f -u $SHIBDUSER -g shibd -y 10 -h $hostname -e $entityID
    fi
    ./metagen.sh -c sp-cert.pem -h $hostname -e $entityID \
    > /tmp/sp_metadata_to_be_edited.xml
}


_setup_shibboleth2_xml() {
    echo ">>generating $etc_path/shibboleth/shibboleth2.xml"
    ${proj_home}/scripts/render_template.py \
        $setupfile \
        ${proj_home}/templates/shibboleth2.xml \
        'Shibboleth2' > $etc_path/shibboleth/shibboleth2.xml
}


_setup_shibboleth2_profile() {
    profile=$( ${proj_home}/scripts/get_config_value.py ${proj_home}/config/express_setup.yaml Shibboleth2 Profile )
    printf ">>copying for profile ${profile} to ${etc_path}/shibboleth/:\n$(ls -l /opt/install/etc/shibboleth/$profile/*)\n"
    cp /opt/install/etc/shibboleth/$profile/* $etc_path/shibboleth/
}


_shibboleth_create_metadata_postprocessor() {
    # create XSLT for processing SP-generated metadata
    ${proj_home}/scripts/render_template.py \
        ${proj_home}/config/express_setup.yaml \
        ${proj_home}/templates/postprocess_metadata.xml \
        'Metadata' > /tmp/postprocess_metadata.xslt
}


_postprocess_metadata() {
    echo ">>post-processing SP metadata into ${metadata_edited}"
    metadata_edited_path=$(cd $(dirname $metadata_edited) && pwd)
    mkdir -p $metadata_edited_path
    xsltproc /tmp/postprocess_metadata.xslt /tmp/sp_metadata_to_be_edited.xml > $metadata_edited
}


_fix_file_privileges() {
    chown -R $HTTPDUSER $etc_path/httpd/*
    chown -R $SHIBDUSER $etc_path/shibboleth/*
}


main $@
