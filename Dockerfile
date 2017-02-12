#FROM centos:centos7
#RUN yum -y install curl httpd ip lsof mod_php mod_ssl net-tools
#COPY install/security:shibboleth.repo /etc/yum.repos.d
# above command cannot be executed on a system with --storage-opt=AUFS
# therefore build shib-spbase on other system and load it:
FROM rhoerbe/shib-spbase
LABEL maintainer="Rainer Hörbe <r2h2@hoerbe.at>" \
      version="0.0.0" \
      # by default, remove all capabilities. You may need to add particular ones, such as:
      #   --cap-add=setuid --cap-add=setgid --cap-add=chown --cap-add=net_bind_service
      capabilites='--cap-drop=all'

# allow build behind firewall
ARG HTTPS_PROXY=''

RUN echo $'export LD_LIBRARY_PATH=/opt/shibboleth/lib64:$LD_LIBRARY_PATH\n' > /etc/sysconfig/shibd \
 && chmod +x /etc/sysconfig/shibd

COPY install/scripts/*.sh /
RUN chmod +x /*.sh


# Run as a non-root user, group root
# Prevent yum to create default uid for shibd to control user mapping between host and container

ARG HTTPDUSER=httpd
ARG HTTPDUID=344005
RUN adduser --gid 0 --uid $HTTPDUID $HTTPDUSER \
 && mkdir -p /var/log/httpd /run/httpd && chown $HTTPDUID:0 /var/log/httpd /run/httpd

ARG SHIBDUSER=shibd
ARG SHIBDUID=343005
RUN adduser --gid 0 --uid $SHIBDUID $SHIBDUSER \
 && mkdir -p /var/log/shibd && chown $SHIBDUID:0 /var/log/shibd && chmod -R g-r /var/log/shibd \
 && yum -y install httpd shibboleth.x86_64 shibboleth-embedded-ds \
 && yum -y clean all \
 && chmod +x /etc/shibboleth/shibd-redhat

CMD /start.sh

VOLUME /etc/httpd
VOLUME /etc/shibboleth
VOLUME /var/log
VOLUME /var/www
