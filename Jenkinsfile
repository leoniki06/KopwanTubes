pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)
  }

  environment {
    DOCKERHUB_USER   = "allysa20"
    IMAGE_NAME       = "kopwan-tubes"
    CREDS_ID         = "dockerhub-credentials"
    TAG              = "${BUILD_NUMBER}"
  }

  stages {
    stage('Checkout') {
      steps {
        retry(3) {
          checkout([
            $class: 'GitSCM',
            branches: [[name: '*/main']],
            userRemoteConfigs: [[url: 'https://github.com/leoniki06/KopwanTubes.git']],
            extensions: [[$class: 'CloneOption', shallow: true, depth: 1, timeout: 30]]
          ])
        }
      }
    }

    stage('Docker Ready') {
      steps {
        bat """
          @echo on
          docker context use default || echo "already default"
          docker version
        """
      }
    }

    stage('Build Image') {
      steps {
        bat """
          @echo on
          echo === root ===
          dir
          echo === moodbite ===
          dir moodbite

          if not exist Dockerfile (
            echo ERROR: Dockerfile tidak ada di root
            exit /b 1
          )

          docker build --no-cache -f Dockerfile -t %IMAGE_NAME%:%TAG% -t %IMAGE_NAME%:latest .
        """
      }
    }

    stage('Push Docker Hub') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: "${CREDS_ID}",
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            @echo on
            echo %DH_TOKEN% | docker login -u %DH_USER% --password-stdin

            docker tag %IMAGE_NAME%:%TAG% %DOCKERHUB_USER%/%IMAGE_NAME%:%TAG%
            docker tag %IMAGE_NAME%:latest %DOCKERHUB_USER%/%IMAGE_NAME%:latest

            docker push %DOCKERHUB_USER%/%IMAGE_NAME%:%TAG%
            docker push %DOCKERHUB_USER%/%IMAGE_NAME%:latest

            docker logout
          """
        }
      }
    }
  }

  post {
    always {
      script {
        try { bat "docker images" } catch (e) { echo "skip: ${e}" }
        try { cleanWs() } catch (e) { echo "skip: ${e}" }
      }
    }
  }
}
