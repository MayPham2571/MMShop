# MMShop

**Prerequisites**
Before opening the project, please ensure the following software is installed on your system:

PHP: Version 8.2 or later
Composer: For PHP package management
Node.js: Version 16 or later
NPM: Comes with Node.js
MySQL: For the database
Git: To clone the repository from master branch

**Steps to Open the Project**
_1. Clone the Repository_
Open your terminal or command prompt and run the following command:
git clone https://github.com/MayPham2571/MMShop.git

_2.Navigate to the Project Folder_

cd Ecommerce

_3.Install Required Dependencies_
- Install PHP dependencies:
composer install

- Install frontend dependencies:
npm install

_4.Set Up Environment Configuration_
- Copy the example environment file and rename it:
cp .env.example .env

- Open the .env file and set up the following:
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
Replace the placeholders (your_database_name, your_username, your_password) with your MySQL details.

_5. Run Database Migrations_
- Set up the database schema by running:
php artisan migrate

_6.Start the Development Server_
Run the following command to launch the project:
php artisan serve

=> By default, the project will be accessible at http://127.0.0.1:8000.

**How to Verify the Project Works**
Open a browser and visit http://127.0.0.1:8000.

Login credentials (if applicable):
Admin Email: admin@gmail.com
Password: admin@gmail.com
