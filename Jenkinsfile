pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo "Checkout source code dari GitHub"
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "Build Docker image"
                bat 'docker build -t kopwantubes:%BUILD_NUMBER% .'
            }
        }

        stage('Push Docker Image') {
            steps {
                echo "Push Docker image"
                // jika belum pakai Docker Hub, stage ini boleh dikosongkan
                bat 'echo Image siap digunakan'
            }
        }
    }

    post {
        success {
            echo "Pipeline selesai ✅"
        }
        failure {
            echo "Build gagal ❌"
        }
    }
}
