pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo 'Checkout repository dari GitHub'
                checkout scm
            }
        }

        stage('Verify Workspace') {
            steps {
                echo 'Menampilkan isi workspace'
                bat 'dir'
            }
        }
    }

    post {
        success {
            echo 'Jenkinsfile berhasil dijalankan ✅'
        }
        failure {
            echo 'Jenkinsfile gagal ❌'
        }
    }
}
