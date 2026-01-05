pipeline {
  agent any
  options { timestamps() }

  environment {
    // Docker Hub (repo = <username>/<image>)
    DOCKERHUB_USER = "allysa20"
    IMAGE_NAME     = "kopwan-tubes"
    IMAGE_TAG      = "${BUILD_NUMBER}"

    // Project kamu ada di folder moodbite/
    DOCKER_CONTEXT  = "moodbite"
    DOCKERFILE_PATH = "moodbite/Dockerfile"
  }

  stages {

    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Show Info') {
      steps {
        script {
          if (isUnix()) {
            sh """
              set -eux
              echo "Commit:"
              git rev-parse HEAD
              ls -la
              ls -la moodbite
            """
          } else {
            bat """
              @echo on
              echo Commit:
              git rev-parse HEAD
              dir
              dir moodbite
            """
          }
        }
      }
    }

    stage('Check Docker') {
      steps {
        script {
          if (isUnix()) {
            sh "docker version"
          } else {
            bat "docker version"
          }
        }
      }
    }

    stage('Build Image') {
      steps {
        script {
          if (isUnix()) {
            sh """
              set -eux
              docker build --no-cache -f ${DOCKERFILE_PATH} -t ${IMAGE_NAME}:${IMAGE_TAG} ${DOCKER_CONTEXT}
              docker tag ${IMAGE_NAME}:${IMAGE_TAG} ${IMAGE_NAME}:latest
            """
          } else {
            bat """
              @echo on
              docker build --no-cache -f %DOCKERFILE_PATH% -t %IMAGE_NAME%:%IMAGE_TAG% %DOCKER_CONTEXT%
              docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
            """
          }
        }
      }
    }

    stage('Login & Push (Docker Hub)') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'dockerhub-creds',
          usernameVariable: 'DH_USER',
          passwordVariable: 'DH_TOKEN'
        )]) {
          script {
            if (isUnix()) {
              sh """
                set -eux
                echo "${DH_TOKEN}" | docker login -u "${DH_USER}" --password-stdin

                docker tag ${IMAGE_NAME}:${IMAGE_TAG} ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG}
                docker tag ${IMAGE_NAME}:latest ${DOCKERHUB_USER}/${IMAGE_NAME}:latest

                docker push ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG}
                docker push ${DOCKERHUB_USER}/${IMAGE_NAME}:latest
              """
            } else {
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
    }
  }

  post {
    always {
      script {
        if (isUnix()) {
          sh """
            docker images | head -n 25 || true
            docker logout || true
          """
        } else {
          bat """
            docker images
            docker logout
          """
        }
      }

      // optional: bersihin workspace
      cleanWs()
    }

    success {
      echo "✅ Build & Push sukses: ${DOCKERHUB_USER}/${IMAGE_NAME}:${IMAGE_TAG} dan :latest"
    }

    failure {
      echo "❌ Pipeline gagal. Lihat log di stage yang error."
    }
  }
}
