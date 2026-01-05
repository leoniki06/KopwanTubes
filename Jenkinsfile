pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)   // supaya checkout kita kontrol sendiri (lebih stabil)
  }

  environment {
    // GANTI username docker hub kamu
    DOCKERHUB_NAMESPACE = 'allysa20'         // contoh, sesuaikan
    APP_IMAGE           = 'kopwan-tubes'
    APP_DIR             = 'moodbite'         // folder laravel di repo kamu
    DOCKERFILE_REL      = 'Dockerfile'       // Dockerfile ada di moodbite/Dockerfile
    DOCKER_CREDS_ID     = 'dockerhub-credentials' // ID credentials di Jenkins
    BUILD_TAG_NUM       = "${BUILD_NUMBER}"
  }

  stages {

    stage('Source') {
      steps {
        // retry untuk mengatasi error "connection reset / early EOF"
        retry(3) {
          checkout([
            $class: 'GitSCM',
            branches: [[name: '*/main']],
            userRemoteConfigs: [[url: 'https://github.com/leoniki06/KopwanTubes.git']],
            extensions: [
              [$class: 'CloneOption', shallow: true, depth: 1, timeout: 30],
              [$class: 'CheckoutOption', timeout: 30]
            ]
          ])
        }
      }
    }

    stage('Preflight') {
      steps {
        bat """
          @echo on
          echo === Repo root ===
          dir

          echo === App dir (%APP_DIR%) ===
          dir %APP_DIR%

          echo === Ensure Docker default context ===
          docker context use default || echo "already default"

          echo === Docker version ===
          docker version
        """
      }
    }

    stage('Build Image') {
      steps {
        bat """
          @echo on
          if not exist %APP_DIR%\\%DOCKERFILE_REL% (
            echo ERROR: Dockerfile not found at %APP_DIR%\\%DOCKERFILE_REL%
            exit /b 1
          )

          echo === Build image from %APP_DIR% ===
          cd %APP_DIR%

          docker build --pull --no-cache ^
            -f %DOCKERFILE_REL% ^
            -t %APP_IMAGE%:%BUILD_TAG_NUM% ^
            -t %APP_IMAGE%:latest ^
            .

          cd ..
        """
      }
    }

    stage('Publish') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: "${DOCKER_CREDS_ID}",
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          bat """
            @echo on
            echo %DH_TOKEN% | docker login -u %DH_USER% --password-stdin

            echo === Retag for Docker Hub namespace ===
            docker tag %APP_IMAGE%:%BUILD_TAG_NUM% %DOCKERHUB_NAMESPACE%/%APP_IMAGE%:%BUILD_TAG_NUM%
            docker tag %APP_IMAGE%:latest %DOCKERHUB_NAMESPACE%/%APP_IMAGE%:latest

            echo === Push ===
            docker push %DOCKERHUB_NAMESPACE%/%APP_IMAGE%:%BUILD_TAG_NUM%
            docker push %DOCKERHUB_NAMESPACE%/%APP_IMAGE%:latest

            docker logout
          """
        }
      }
    }

    stage('Result') {
      steps {
        echo "IMAGE: ${DOCKERHUB_NAMESPACE}/${APP_IMAGE}:${BUILD_TAG_NUM}"
        echo "IMAGE: ${DOCKERHUB_NAMESPACE}/${APP_IMAGE}:latest"
      }
    }
  }

  post {
    always {
      script {
        // post dibuat aman supaya tidak error kalau checkout gagal
        try { bat "docker images" } catch (e) { echo "Skip docker images: ${e}" }
        try { cleanWs() } catch (e) { echo "Skip cleanWs: ${e}" }
      }
    }
  }
}
