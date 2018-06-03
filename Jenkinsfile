// Stand-alone test (cleanup before and after; does not retain container or persistent volumes)
pipeline {
    agent any
    options { disableConcurrentBuilds() }
    parameters {
        string(defaultValue: '', description: 'Force "docker build --nocache" (blank or 1)', name: 'nocache')
        string(description: 'push docker image after build (blank or 1)', name: 'pushimage')
        string(description: 'overwrite default docker registry user', name: 'docker_registry_user')
        string(description: 'overwrite default docker registry host', name: 'docker_registry_host')
    }

    stages {
        stage('Cleanup container, volumes') {
            steps {
                sh '''
                    rm conf.sh 2> /dev/null || true
                    ln -sf conf.sh.default conf.sh
                    ./dscripts/manage.sh rm 2>/dev/null || true
                    ./dscripts/manage.sh rmvol 2>/dev/null || true
                '''
            }
        }
        stage('Build parent image') {
            steps {
                echo 'Job d-shibspbase: Building parent image ..'
                build job: 'd-shibspbase'
            }
        }
        stage('Build') {
            steps {
                sh '''
                    echo "=== Building $IMAGENAME (docker_registry_user=$docker_registry_user; docker_registry_host=$docker_registry_host"
                    rm conf.sh 2> /dev/null || true
                    cp conf.sh.default conf.sh
                    echo '#!/bin/bash'  > local_conf.sh
                    echo '[[ "'$docker_registry_user'" ]] && export DOCKER_REGISTRY_USER=$docker_registry_user'  >> local_conf.sh
                    echo '[[ "'$docker_registry_host'" ]] && export DOCKER_REGISTRY=$docker_registry_host'  >> local_conf.sh
                    echo 'return'  >> local_conf.sh
                    source ./conf.sh
                    echo "conf.sh sourced"
                    [[ "$pushimage" ]] && pushopt='-P'
                    [[ "$nocache" ]] && nocacheopt='-c'
                    bash -x ./dscripts/build.sh -p $nocacheopt $pushopt
                    echo "=== build completed with rc $?"
                '''
            }
        }
        stage('Setup + Run') {
            steps {
                sh '''#!/bin/bash
                    echo "Setup persistent volumes unless already setup"
                    ./dscripts/manage.sh statcode
                    is_running=$?
                    ./dscripts/exec.sh -iR /opt/bin/is_initialized.sh
                    is_init=$?
                    if (( $is_init != 0 )); then
                        >&2 echo "setup test config"
                        ./dscripts/run.sh -iC 'cp /opt/install/config/express_setup_citest.yaml \
                                                  /opt/etc/express_setup_citest.yaml'
                        ./dscripts/run.sh -iC /opt/install/scripts/express_setup.sh -s express_setup_citest.yaml
                        >&2 echo "start server"
                        ./dscripts/run.sh
                        ./dscripts/manage.sh logs
                    else
                        >&2 echo 'skipping setup - already done'
                        if (( $is_running > 0 )); then
                            >&2 echo "start server"
                            ./dscripts/run.sh
                        fi
                    fi
                '''
            }
        }
        stage('Test') {
            steps {
                sh '''
                    sleep 1
                    ./dscripts/exec.sh -i /opt/install/tests/test_sp.sh
                '''
            }
        }
        stage('Push ') {
            steps {
                sh '''
                    sleep 1
                    ./dscripts/manage.sh -p push
                '''
            }
        }
    }
    post {
        always {
            echo 'Remove container, volumes'
            sh '''
                ./dscripts/manage.sh rm 2>/dev/null || true
                ./dscripts/manage.sh rmvol 2>/dev/null || true
            '''
        }
    }
}