# Shibboleth SP docker image  

A Shibboleth configuration running apache httpd and shibd in a container:

1. Mapping users (apache and shibd) between host & container filesystems (also with multiple container instances)
2. The user-defined network uses static IP addresses.
3. Image is based on centos7
4. The image produces immutable containers, i.e. a container can be removed and re-created
any time without loss of data, because data is stored on mounted volumes.

## Build the docker image

1. adapt conf.sh
2. run build.sh 


## Usage:
create container:
 
    run.sh -h  # print usage
    run.sh -ipr bash  # interactive, print run command, root user 
    run.sh     # daemon mode
    
## HTTPD Configuration 

- The default apache httpd configuration is created in /etc/httpd during docker build, whereas /start.sh will
  change the ServerRoot to /opt/etc/httpd
- To copy the default configuration there are 2 options:
  -- start the container with a shell and `cp -pr /etc/httpd /opt/etc/`, or
  -- copy the configuration from a template you have from somewhere else 
- Check/modify the /opt/etc/httpd/conf.d/vhost.conf
- Set the httpd user/group in conf/httpd.conf to that defined in conf*.sh

- If the apache httpd is not internet facing, setup the reverse proxy in the nginx container (optional)


## Shibboleth SP Configuration
- The default shibd configuration is created in /etc/shibboleth during docker build, whereas /start.sh will
  change the ServerRoot to /opt/etc/shibboleth.
- To copy the default configuration there are 2 options:
  -- start the container with a shell and `cp -pr /etc/shibboleth /opt/etc/`, or
  -- copy the configuration from a template you have from somewhere else 
- Check/modify the config files in /opt/etc/shibboleth according to the documentation, optionally
  create new keys and metadata (keygen.sh, metagen.sh)
