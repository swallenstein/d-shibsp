#FROM centos:centos7
#RUN yum -y install curl httpd ip lsof mod_php mod_ssl net-tools
#COPY install/security:shibboleth.repo /etc/yum.repos.d
# above command cannot be executed on a system with --storage-opt=AUFS
# therefore build shib-spbase on other system and load it:
FROM rhoerbe/shib-spbase
MAINTAINER r2h2 <rainer@hoerbe.at>   # credits to John Gasper <jtgasper3@gmail.com>


RUN echo $'export LD_LIBRARY_PATH=/opt/shibboleth/lib64:$LD_LIBRARY_PATH\n' > /etc/sysconfig/shibd \
 && chmod +x /etc/sysconfig/shibd

COPY install/scripts/*.sh /
RUN chmod +x /*.sh

# prevent yum to create default uid for shibd to control user mapping between host and container
ARG SHIBDUSER
ARG SHIBDUID
RUN groupadd --gid $SHIBDUID $SHIBDUSER \
 && adduser --gid $SHIBDUID --uid $SHIBDUID $SHIBDUSER \
 && yum -y install shibboleth.x86_64 shibboleth-embedded-ds \
 && yum -y clean all \
 && chmod +x /etc/shibboleth/shibd-redhat

# config will be mapped into /opt/etc/httpd. Remove original dir to avoid misconfiguration
RUN mv /etc/httpd /etc/httpd.default

## Service will run as a non-root user/group that must map to the docker host
ARG HTTPDUSER
ARG HTTPDUID
RUN groupadd --gid $HTTPDUID $HTTPDUSER \
 && adduser --gid $HTTPDUID --uid $HTTPDUID $HTTPDUSER \
 && mkdir -p /run/httpd && chown $HTTPDUSER:$HTTPDUSER /run/httpd

CMD /start.sh