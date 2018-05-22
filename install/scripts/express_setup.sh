#!/usr/bin/env bash

main() {
    echo ">>running express setup"
    _get_commandline_opts $@
    _default_config_for_ci
    _setup_httpd
    _shibboleth_gen_keys_and_metadata
    _setup_shibboleth2_xml
    _setup_shibboleth2_profile
    _shibboleth_create_metadata_postprocessor
    _postprocess_metadata
    _fix_file_privileges
}


_get_commandline_opts() {
    metadata_edited="sp_metadata.xml"
    setupfilebasename=express_setup.yaml
    while getopts ":ko:s:" opt; do
      case $opt in
        k) keygen='True';;
        o) metadata_edited=$OPTARG;;
        s) setupfilebasename=$OPTARG;;
        :) echo "Option -$OPTARG requires an argument"; exit 1;;
        *) echo "usage: $0 OPTIONS
           OPTIONS:
           -k  overwrite existing SP signature keys
           -o  filename of post-processed metadata file for federation registration (default: $metadata_edited)
           -s  setup file for express configuration (default: $setupfile)
           "; exit 0;;
      esac
    done
    shift $((OPTIND-1))
    setupfile=/opt/etc/$setupfilebasename
}


_default_config_for_ci() {
    # use default config if no custom config is found (only useful for CI-tests)
    if [[ ! -e $setupfile ]]; then
        echo "No custom express setup configuration found; using /opt/install/config/express_setup.yaml"
        mkdir -p /opt/etc
        cp -n /opt/install/config/express_setup.yaml $setupfile
        cat /opt/install/etc/hosts.d/testdom.test >> /etc/hosts  # FQDNs for default config
    fi
}


_setup_httpd() {
    hostname=$( /opt/bin/get_config_value.py $setupfile httpd hostname )
    echo ">>generating httpd config for ${hostname}"
    sed -e "s/^User httpd$/User $HTTPDUSER/" /opt/install/etc/httpd/httpd.conf > /etc/httpd/conf/httpd.conf
    sed -e "s/sp.example.org/$hostname/" /opt/install/etc/httpd/conf.d/vhost.conf > /etc/httpd/conf.d/vhost.conf
    cp -n /opt/install/etc/httpd/conf.d/* /etc/httpd/conf.d/
    echo "PidFile /var/log/httpd/httpd.pid" > /etc/httpd/conf.d/pidfile.conf
}


_shibboleth_gen_keys_and_metadata() {
    entityID=$( /opt/bin/get_config_value.py $setupfile Shibboleth2 entityID )
    hostname=$( /opt/bin/get_config_value.py $setupfile httpd hostname )
    echo "generate SP signing key pair and metadata for host=$hostname and entityID=$entityID"
    cd /etc/shibboleth
    if [[ -e sp-cert.pem ]]; then
        if [[ $keygen ]]; then
            ./keygen.sh -f -u $SHIBDUSER -g shibd -y 10 -h $hostname -e $entityID
        else
            echo "using existing signing key (sp_cert.pem). use -k to force re-generationg of key"
        fi
    else
        touch sp-cert.pem sp-^key.pem
        ./keygen.sh -f -u $SHIBDUSER -g shibd -y 10 -h $hostname -e $entityID
    fi
    ./metagen.sh -c sp-cert.pem -h $hostname -e $entityID > /tmp/sp_metadata_to_be_edited.xml
}


_setup_shibboleth2_xml() {
    echo ">>generating /etc/shibboleth/shibboleth2.xml"
    /opt/bin/render_template.py $setupfile /opt/install/templates/shibboleth2.xml 'Shibboleth2' \
        > /etc/shibboleth/shibboleth2.xml
}


_setup_shibboleth2_profile() {
    profile=$( /opt/bin/get_config_value.py ${setupfile} Shibboleth2 Profile )
    printf ">>copying for profile ${profile} to /etc/shibboleth/:\n$(ls -l /opt/install/etc/shibboleth/$profile/*)\n"
    cp /opt/install/etc/shibboleth/$profile/* /etc/shibboleth/
}


_shibboleth_create_metadata_postprocessor() {
    echo "create XSLT for processing SP-generated metadata (/tmp/postprocess_metadata.xslt)"
    /opt/bin/render_template.py $setupfile /opt/install/templates/postprocess_metadata.xml 'Metadata' \
        > /tmp/postprocess_metadata.xslt
}


_postprocess_metadata() {
    echo ">>post-processing SP metadata"
    metadata_edited_path=/etc/shibboleth/export
    mkdir -p $metadata_edited_path
    xsltproc /tmp/postprocess_metadata.xslt /tmp/sp_metadata_to_be_edited.xml \
        > $metadata_edited_path/$metadata_edited
    echo "created ${metadata_edited_path}/${metadata_edited}"
}


_fix_file_privileges() {
    chown -R $HTTPDUSER:shibd /etc/httpd/ /run/httpd/ /var/log/httpd/
    chown -R $SHIBDUSER:shibd /etc/shibboleth/ /var/log/shibboleth/
    chmod -R 775  /run/httpd/
    chmod -R 755  /var/log/shibboleth/
}


main $@
