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
          echo === Git Commit Used ===
          git rev-parse HEAD
          git log -1 --oneline

          echo === Root Files ===
          dir

          echo === Dockerfile Content ===
          type Dockerfile
        """
      }
    }

    stage('Build Docker Image') {
      steps {
        bat """
          echo === Docker Version ===
          docker version

          echo === Build Image (no-cache) ===
          docker build --no-cache -t %IMAGE_NAME%:%IMAGE_TAG% .

          echo === Tag Latest ===
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Login Docker Hub') {
      when {
        expression { currentBuild.currentResult == 'SUCCESS' }
      }
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            echo === Docker Hub Login ===
            echo %DH_TOKEN% | docker login -u %DH_USER% --password-stdin
          """
        }
      }
    }

    stage('Tag & Push Docker Image') {
      when {
        expression { currentBuild.currentResult == 'SUCCESS' }
      }
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            echo === Tag for Docker Hub ===
            docker tag %IMAGE_NAME%:%IMAGE_TAG% %DH_USER%/%IMAGE_NAME%:%IMAGE_TAG%
            docker tag %IMAGE_NAME%:latest %DH_USER%/%IMAGE_NAME%:latest

            echo === Push to Docker Hub ===
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
        echo === Local images (if any) ===
        docker images | findstr %IMAGE_NAME% || echo "No image found (build may have failed)."

        echo === Logout (optional) ===
        docker logout || echo "Not logged in."
      """
    }
  }
}
