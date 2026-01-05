pipeline {
  agent any
  options { timestamps() }

  environment {
    DOCKERHUB_USER = "allysa20"
    IMAGE_NAME     = "kopwan-tubes"
    IMAGE_TAG      = "${BUILD_NUMBER}"

    BUILD_FOLDER   = "moodbite"
  }

  stages {

    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Force Docker default context') {
      steps {
        bat """
          @echo on
          docker context ls
          docker context use default || echo "default already"
          docker version
        """
      }
    }

    stage('Verify Project Structure') {
      steps {
        bat """
          @echo on
          echo === Root ===
          dir

          echo === moodbite ===
          dir %BUILD_FOLDER%

          echo === Check Dockerfile ===
          dir %BUILD_FOLDER%\\Dockerfile
        """
      }
    }

    stage('Build Image') {
      steps {
        bat """
          @echo on
          if not exist %BUILD_FOLDER%\\Dockerfile (
            echo ERROR: %BUILD_FOLDER%\\Dockerfile NOT FOUND
            exit /b 1
          )

          cd %BUILD_FOLDER%
          docker build --no-cache -f Dockerfile -t %IMAGE_NAME%:%IMAGE_TAG% .
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
  }
}
