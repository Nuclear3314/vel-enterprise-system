<<<<<<< HEAD
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
=======
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
>>>>>>> b29bd98ae45cfc679c1a703fb927eca56e44b11c
}