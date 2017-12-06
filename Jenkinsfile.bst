// Build-Setup-Test (no prior cleanup; leave container running after test)
pipeline {
    agent any
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
                    ./dscripts/build.sh -p
                '''
            }
        }
        stage('Setup: Setup persistent volumes') {
            steps {
                sh '''#!/bin/bash
                    echo "test if already setup"
                    ./dscripts/manage.sh statcode
                    is_running=$?
                    if (( $is_running > 0 )); then
                        ./dscripts/run.sh -i /scripts/is_initialized.sh
                        is_init=$?
                    else
                        ./dscripts/exec.sh -i /scripts/is_initialized.sh
                        is_init=$?
                    fi
                    if (( $is_init != 0 )); then
                        >&2 echo "setup test config"
                        ./dscripts/run.sh -iC /opt/install/scripts/express_setup.sh
                        if (( $is_running > 0 )); then
                            >&2 echo "start server"
                            ./dscripts/run.sh
                            ./dscripts/manage.sh logs
                        fi
                    else
                        >&2 echo 'skipping  - already setup'
                    fi
                '''
            }
        }
        stage('Test') {
            steps {
                sh '''
                    ./dscripts/exec.sh -i opt/install/tests/test_sp.sh
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