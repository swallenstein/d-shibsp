// Build-Setup-Test (no prior cleanup; leave container running after test)
pipeline {
    agent any
    parameters {
        string(defaultValue: '', description: 'Force "docker build --nocache"', name: 'nocache')
        string(defaultValue: '', description: 'push docker image after build', name: 'pushimage')
    }
    stages {
        stage('Build parent image') {
            steps {
                echo 'Job d-shibspbase: Building parent image ..'
                build job: 'd-shibspbase'
            }
        }
        stage('Build') {
            steps {
                sh '''
                    echo 'Build image'
                    rm conf.sh 2> /dev/null || true
                    ln -sf conf.sh.default conf.sh
                    if [[ $pushimage ]]; then
                        pushopt='-P'
                    fi
                    echo 'Build image'
                    [[ "$pushimage" ]] && pushopt='-P'
                    [[ "$nocache" ]] && nocacheopt='-c'
                    ./dscripts/build.sh -p $nocacheopt $pushopt
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
          echo 'container status'
          sh './dscripts/manage.sh status'
        }
    }
}