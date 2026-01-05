pipeline {
  agent any

  options {
    timestamps()
  }

  environment {
    IMAGE_NAME = "kopwan-tubes"   // nama image (bebas)
    IMAGE_TAG  = "${BUILD_NUMBER}"
  }

  stages {

    stage('Checkout Source') {
      steps {
        checkout scm
      }
    }

    stage('Build Docker Image') {
      steps {
        bat """
          echo === Docker Info ===
          docker version

          echo === Build Image ===
          docker build -t %IMAGE_NAME%:%IMAGE_TAG% .

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
        echo === Docker Images (if exists) ===
        docker images | findstr %IMAGE_NAME% || echo "No image found yet (build may have failed)."

        echo === Docker Hub Logout ===
        docker logout || echo "Already logged out / not logged in."
      """
    }

    success {
      echo "SUCCESS: Build & Push selesai."
    }

    failure {
      echo "FAILED: Cek Console Output di stage 'Build Docker Image' dulu (biasanya Dockerfile/composer error)."
    }
  }
}
