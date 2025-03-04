pipeline {
    agent any
    
    stages {
        stage('Build') {
            steps {
                bat 'python -m pip install --upgrade pip'
                bat 'pip install -r requirements.txt'
            }
        }
        
        stage('Test') {
            steps {
                bat 'python -m pytest tests/'
            }
        }
        
        stage('Deploy') {
            when {
                branch 'main'
            }
            steps {
                bat 'python deploy/deploy.py'
            }
        }
    }
}