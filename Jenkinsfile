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

    stage('Debug - Show Structure') {
      steps {
        bat """
          echo === Git Commit Used ===
          git rev-parse HEAD
          git log -1 --oneline

          echo === Root Files ===
          dir

          echo === moodbite Files ===
          dir moodbite

          echo === Dockerfile Used (moodbite\\\\Dockerfile) ===
          type moodbite\\Dockerfile
        """
      }
    }

    stage('Build Docker Image') {
      steps {
        bat """
          echo === Docker Version ===
          docker version

          echo === Build Image (no-cache) from moodbite/ ===
          docker build --no-cache -t %IMAGE_NAME%:%IMAGE_TAG% -f moodbite/Dockerfile moodbite

          echo === Tag Latest ===
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Login Docker Hub') {
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
