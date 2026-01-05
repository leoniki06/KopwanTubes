pipeline {
  agent any
  options { timestamps() }

  environment {
    IMAGE_NAME = "kopwan-tubes"
    IMAGE_TAG  = "${BUILD_NUMBER}"
  }

  stages {
    stage('Checkout Source') {
      steps { checkout scm }
    }

    stage('Debug - Show Dockerfile') {
      steps {
        bat """
          echo === Git status ===
          git rev-parse HEAD
          git log -1 --oneline

          echo === List root files ===
          dir

          echo === Dockerfile content ===
          type Dockerfile
        """
      }
    }

    stage('Build Docker Image') {
      steps {
        bat """
          docker version
          docker build --no-cache -t %IMAGE_NAME%:%IMAGE_TAG% .
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Login Docker Hub') {
      when { succeeded() }
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat "echo %DH_TOKEN% | docker login -u %DH_USER% --password-stdin"
        }
      }
    }

    stage('Tag & Push Docker Image') {
      when { succeeded() }
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            docker tag %IMAGE_NAME%:%IMAGE_TAG% %DH_USER%/%IMAGE_NAME%:%IMAGE_TAG%
            docker tag %IMAGE_NAME%:latest %DH_USER%/%IMAGE_NAME%:latest
            docker push %DH_USER%/%IMAGE_NAME%:%IMAGE_TAG%
            docker push %DH_USER%/%IMAGE_NAME%:latest
          """
        }
      }
    }
  }

  post {
    always {
      bat """
        docker images | findstr %IMAGE_NAME% || echo "No image found (build failed)."
      """
    }
  }
}
