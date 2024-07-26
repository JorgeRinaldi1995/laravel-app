pipeline {
    agent any
    stages {
        stage('Setup Known Hosts') {
            steps {
                script {
                    sh 'mkdir -p ~/.ssh'
                    sh 'ssh-keyscan github.com >> ~/.ssh/known_hosts'
                }
            }
        }
        stage("Verify tooling") {
            steps {
                sh '''
                    docker info
                    docker version
                    docker compose version
                '''
            }
        }
        stage("Verify SSH connection to server") {
            steps {
                sshagent(credentials: ['aws-ec2']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ec2-user@54.146.74.33 whoami
                    '''
                }
            }
        }        
        stage("Clear all running docker containers") {
            steps {
                script {
                    try {
                        sh 'docker rm -f $(docker ps -a -q)'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage("Start Docker") {
            steps {
                sh 'docker-compose up -d'
                sh 'docker compose ps'
            }
        }
        stage("Run Composer Install") {
            steps {
                sh 'docker-compose exec -T --user root app composer install'
            }
        }
        stage("Populate .env file") {
            steps {
                echo "Current workspace: ${WORKSPACE}"
                dir("/var/lib/jenkins/workspace/envs/laravel-app") {
                    echo "Current directory: ${pwd()}"
                    sh 'ls -l' // List files in the directory to confirm the .env file presence
                    fileOperations([fileCopyOperation(excludes: '', flattenFiles: true, includes: '.env', targetLocation: "${WORKSPACE}")]) // Remember to install the File Operations Plugin
                }
            }
        }              
        stage("Run Tests") {
            steps {
                sh 'docker-compose exec -T --user root app php artisan test'
            }
        }
    }
    post {
        success {
            echo "go to Laravel App folder"
            sh 'cd "/var/lib/jenkins/workspace/LaravelApp"'
            echo "remove artifact.zip"
            sh 'rm -rf artifact.zip'
            echo "im not sure..."
            sh 'zip -r artifact.zip . -x "*node_modules**"'
            withCredentials([sshUserPrivateKey(credentialsId: "aws-ec2", keyFileVariable: 'keyfile')]) {
                sh 'scp -v -o StrictHostKeyChecking=no -i ${keyfile} /var/lib/jenkins/workspace/LaravelApp/artifact.zip ec2-user@54.146.74.33:/home/ec2-user/artifact'
            }
            sshagent(credentials: ['aws-ec2']) {
                sh 'ssh -o StrictHostKeyChecking=no ec2-user@54.146.74.33 unzip -o /home/ec2-user/artifact/artifact.zip -d /var/www/html'
                script {
                    try {
                        sh 'ssh -o StrictHostKeyChecking=no ec2-user@54.146.74.33 sudo chmod 777 /var/www/html/storage -R'
                    } catch (Exception e) {
                        echo 'Some file permissions could not be updated.'
                    }
                }
            }                                  
        }
        always {
            sh 'docker compose down --remove-orphans -v'
            sh 'docker compose ps'
        }
    }
}