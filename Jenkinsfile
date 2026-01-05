pipeline {
  agent any

  environment {
    DOCKERHUB_USER = "dockerhubusername"   // GANTI
    IMAGE_NAME     = "kopwan-tubes"
    IMAGE_TAG      = "${BUILD_NUMBER}"
  }

  stages {

    stage('Checkout Source') {
      steps {
        checkout scm
      }
    }

    stage('Build Docker Image') {
      steps {
        sh """
          docker build -t ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG} .
          docker tag ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG} ${DOCKERHUB_USER}/${IMAGE_NAME}:latest
        """
      }
    }

    stage('Login Docker Hub') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DOCKER_USER',
          passwordVariable: 'DOCKER_PASS'
        )]) {
          sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
        }
      }
    }

    stage('Push Docker Image') {
      steps {
        sh """
          docker push ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG}
          docker push ${DOCKERHUB_USER}/${IMAGE_NAME}:latest
        """
      }
    }
  }

  post {
    success {
      echo "SUCCESS: Docker image pushed to Docker Hub"
    }
    failure {
      echo "FAILED: Pipeline error"
    }
  }
}
