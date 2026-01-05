pipeline {
  agent any
  options { timestamps() }

  environment {
    DOCKERHUB_USER = "allysa20"
    IMAGE_NAME     = "kopwan-tubes"
    IMAGE_TAG      = "${BUILD_NUMBER}"

    // ini folder project, JANGAN pakai nama DOCKER_CONTEXT
    BUILD_CONTEXT  = "moodbite"
    DOCKERFILE_PATH = "moodbite/Dockerfile"
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Force Docker to default context') {
      steps {
        bat """
          @echo on
          echo === Docker contexts ===
          docker context ls

          echo === Force use default context ===
          docker context use default || echo "default context already used"

          echo === Docker version ===
          docker version
        """
      }
    }

    stage('Build Image') {
      steps {
        bat """
          @echo on
          docker build --no-cache -f %DOCKERFILE_PATH% -t %IMAGE_NAME%:%IMAGE_TAG% %BUILD_CONTEXT%
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Login & Push Docker Hub') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            @echo on
            echo %DH_TOKEN% | docker login -u %DH_USER% --password-stdin

            docker tag %IMAGE_NAME%:%IMAGE_TAG% %DOCKERHUB_USER%/%IMAGE_NAME%:%IMAGE_TAG%
            docker tag %IMAGE_NAME%:latest %DOCKERHUB_USER%/%IMAGE_NAME%:latest

            docker push %DOCKERHUB_USER%/%IMAGE_NAME%:%IMAGE_TAG%
            docker push %DOCKERHUB_USER%/%IMAGE_NAME%:latest
          """
        }
      }
    }
  }

  post {
    always {
      bat """
        @echo on
        docker images
        docker logout
      """
      cleanWs()
    }

    success { echo "✅ Sukses push: %DOCKERHUB_USER%/%IMAGE_NAME%:%IMAGE_TAG% dan :latest" }
    failure { echo "❌ Gagal. Cek error stage Docker." }
  }
}
