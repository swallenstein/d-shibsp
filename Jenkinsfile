// Stand-alone test (cleanup before and after; does not retain container or persistent volumes)
// requires python3 + pyyaml + jinja2
pipeline {
    agent any
    options { disableConcurrentBuilds() }
    parameters {
        string(defaultValue: 'True', description: '"True": initial cleanup: remove container and volumes; otherwise leave empty', name: 'start_clean')
        string(description: '"True": "Set --nocache for docker build; otherwise leave empty', name: 'nocache')
        string(description: '"True": push docker image after build; otherwise leave empty', name: 'pushimage')
        string(description: '"True": keep running after test; otherwise leave empty to delete container and volumes', name: 'keep_running')
        string(description: '"True": overwrite default docker registry user; otherwise leave empty', name: 'docker_registry_user')
        string(description: '"True": overwrite default docker registry host; otherwise leave empty', name: 'docker_registry_host')
    }

    stages {
        stage('Cleanup container, volumes') {
            steps {
                sh '''
                    rm conf.sh 2> /dev/null || true
                    ln -sf conf.sh.default conf.sh
                    if [[ "$start_clean" ]]; then
                        ./dscripts/manage.sh rm 2>/dev/null || true
                        ./dscripts/manage.sh rmvol 2>/dev/null || true
                    fi
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
                echo "==========================="
                sh 'set +x; source ./conf.sh; echo "Building $IMAGENAME"'
                echo "Pipeline args: nocache=$nocache; pushimage=$pushimage; docker_registry_user=$docker_registry_user; docker_registry_host=$docker_registry_host"
                echo "==========================="
                sh '''
                    set +x
                    echo [[ "$docker_registry_user" ]] && echo "DOCKER_REGISTRY_USER $docker_registry_user"  > local.conf
                    echo [[ "$docker_registry_host" ]] && echo "DOCKER_REGISTRY_HOST $docker_registry_host"  >> local.conf
                    [[ "$pushimage" ]] && pushopt='-P'
                    [[ "$nocache" ]] && nocacheopt='-c'
                    ./dscripts/build.sh -p $nocacheopt $pushopt
                '''
                     sh '''
                    echo "generate run script"
                    ./dscripts/run.sh -w
                    echo "create docker-compose.yaml"
                    dscripts/gen_compose_yaml.sh
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
                    cp work/docker-compose.yaml .
                    if (( $is_init != 0 )); then
                        >&2 echo "setup test config"
                        ./dscripts/run.sh -iC 'cp /opt/install/config/express_setup_citest.yaml \
                                                  /opt/etc/express_setup_citest.yaml'
                        ./dscripts/run.sh -iC /opt/install/scripts/express_setup.sh -s express_setup_citest.yaml
                        >&2 echo "start server"
                        docker-compose up -d
                        ./dscripts/manage.sh logs
                    else
                        >&2 echo 'skipping setup - already done'
                        if (( $is_running > 0 )); then
                            >&2 echo "start server"
                            docker-compose up -d
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
    }
    post {
        always {
            echo 'removing docker volumes and container '
            sh '''
                if [[ "$keep_running" ]]; then
                    echo "Keep container running"
                else
                    ./dscripts/manager.sh rm 2>/dev/null || true
                    ./dscripts/manager.sh rmvol 2>/dev/null || true
                fi
            '''
        }
    }
}