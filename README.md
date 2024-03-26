
# Laravel Project Readme
## Project Overview
This Laravel project is a coding task assigned to me by EnactOn as part of the interview process for the PHP Laravel Developer role. The project aims to demonstrate my proficiency in Laravel development and showcase my coding skills.

## Project Structure
The project follows a standard Laravel folder structure, with key directories and files organized as follows:

```
laravel-project/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   ├── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeds/
├── public/
├── resources/
│   ├── lang/
│   ├── views/
├── routes/
├── storage/
├── tests/
├── vendor/
├── .env
├── .env.example
├── artisan
└── README.md
```
## Installation and Setup
To set up and run this Laravel project locally, follow these steps:

Clone the repository to your local machine:
```
git clone https://github.com/your-username/laravel-project.git
```
Navigate to the project directory:
```
cd laravel-project
```
Install project dependencies using Composer:
```
composer install
```
Create a copy of the .env.example file and rename it to .env:
```
cp .env.example .env
```
For Windows:
```
copy .env.example .env
```

Generate the application key:
```
php artisan key:generate
```

Configure the database connection in the .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
Run the database migrations:
```
php artisan migrate
```
Serve the application:
```
php artisan serve
```
The Laravel project should now be up and running locally at http://localhost:8000.

## Links
- GitHub Repo for the code: [EnactOn Laravel Interview Coding Solution](https://github.com/Aayushyamaan-Shah/EnactOn-Laravel-Interview-Coding-Solution)
- GitHub: [Aayushyamaan Shah](https://github.com/Aayushyamaan-Shah/)
- Portfolio Website: [My Portfolio Website](https://aayushyamaan-shah.vercel.app/)
- Videos related to the project: [Video Folder](https://drive.google.com/drive/folders/1ZbSeTLL46pXyFGUWW6X4T5TjHcGeszKs?usp=sharing)
- Main Project Folder: [Main Folder](https://drive.google.com/drive/folders/14ORP_s1AoA174WsD_3LkTA--b_5MWhl0?usp=sharing)

## Additional Notes
This README serves as a basic guide to set up and run the Laravel project locally.

For any additional assistance or inquiries regarding the project, feel free to contact me at aayushs474@gmail.com.

Thank you for considering my application. I look forward to discussing the project further and showcasing my Laravel development skills.
