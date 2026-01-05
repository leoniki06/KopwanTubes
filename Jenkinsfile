pipeline {
    agent any

    environment {
        // Tools di Jenkins (pastikan sudah diinstall di Global Tool Configuration)
        PHP_HOME = tool 'PHP' 
        PATH = "${env.PHP_HOME}/bin:${env.PATH}"
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Checkout repository dari GitHub"
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo "Install PHP dependencies lewat Composer"
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Run Tests') {
            steps {
                echo "Menjalankan Laravel PHPUnit tests"
                sh './vendor/bin/phpunit'
            }
        }

        stage('Build Docker Image') {
            when {
                expression { fileExists('Dockerfile') }
            }
            steps {
                echo "Build dan Push Docker image"
                sh '''
                  docker build -t registry.example.com/kopwantubes:${env.BUILD_NUMBER} .
                  docker push registry.example.com/kopwantubes:${env.BUILD_NUMBER}
                '''
            }
        }
    }

    post {
        success {
            echo "Pipeline sukses 🎉"
        }
        failure {
            echo "Build gagal ❌"
        }
        always {
            echo "Pipeline selesai ✅"
        }
    }
}
