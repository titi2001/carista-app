Carista App

A demo project for Carista

Prerequisites

Make sure you have the following installed on your system:

PHP >= 8.x

Composer

Node.js & npm/yarn

MySQL

Installation

Clone the repository

git clone https://github.com/titi2001/carista-app.git
cd carista-app


Install PHP dependencies

composer install


Install front-end dependencies

npm install


Create .env file

cp .env.example .env


Set your environment variables in .env

Add your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password


Add your News API key:

NEWS_API_KEY=your_api_key_here


Generate application key

php artisan key:generate


Run migrations

php artisan migrate

Running the Project

Start the Laravel server

php artisan serve


The project will be available at: http://127.0.0.1:8000

Compile front-end assets
npm run dev

Usage

Open the app in your browser.

Use the search form to query the News API.

The results are paginated and displayed with charts

Notes

Troubleshooting

Missing dependencies: Run composer install and npm install again.

Env variables not loaded: Make sure .env exists and php artisan config:cache is cleared.

License

This project is licensed under the MIT License.