pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)   // <-- penting! matikan checkout SCM bawaan Jenkins
  }

  environment {
    DOCKERHUB_USER     = "allysa20"
    IMAGE_NAME         = "kopwan-tubes"
    DOCKER_CREDENTIALS = "dockerhub-credentials"
    IMAGE_TAG          = "${BUILD_NUMBER}"
    BUILD_FOLDER       = "moodbite"
  }

  stages {

    stage('Checkout') {
      steps {
        retry(3) {   // <-- kalau koneksi putus, coba lagi otomatis
          checkout([
            $class: 'GitSCM',
            branches: [[name: '*/main']],
            userRemoteConfigs: [[url: 'https://github.com/leoniki06/KopwanTubes.git']],
            extensions: [
              [$class: 'CloneOption', shallow: true, depth: 1, noTags: false, timeout: 30],
              [$class: 'CheckoutOption', timeout: 30]
            ]
          ])
        }
      }
    }

    stage('Docker OK?') {
      steps {
        bat """
          @echo on
          docker context use default
          docker version
        """
      }
    }

    stage('Build Image') {
      steps {
        bat """
          @echo on
          if not exist %BUILD_FOLDER%\\Dockerfile (
            echo ERROR: %BUILD_FOLDER%\\Dockerfile NOT FOUND
            dir %BUILD_FOLDER%
            exit /b 1
          )

          cd %BUILD_FOLDER%
          docker build --no-cache -f Dockerfile -t %IMAGE_NAME%:%IMAGE_TAG% .
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Push Docker Hub') {
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
      script {
        // Jangan bikin pipeline error gara-gara post action
        try { bat "docker images" } catch (e) { echo "Skip docker images: ${e}" }
        try { bat "docker logout" } catch (e) { echo "Skip docker logout: ${e}" }
        try { cleanWs() } catch (e) { echo "Skip cleanWs: ${e}" }
      }
    }
  }
}
