# CyberCore E-Learning Platform

<p align="center">
  <img src="public/logo.png" width="120" alt="CyberCore Logo">
</p>
<p align="center">
  <strong>A modern, secure e-learning platform for cybersecurity education.</strong>
</p>

---

## About CyberCore

CyberCore is a web-based e-learning application designed to provide accessible, engaging, and effective cybersecurity training. It's built on the Laravel framework and focuses on providing a clean, modern user experience for students, lecturers, and administrators.

The platform allows lecturers to create and manage their own courses, while administrators oversee the entire system. Students can enroll in courses, take quizzes, track their progress, and earn certificates.

## Key Features

- **Role-Based Access Control:**
  - **Admin:** Full control over all courses, users, and system settings.
  - **Lecturer:** Can create, manage, and edit their own courses.
  - **Student:** Can enroll in courses, complete lessons, and take quizzes.
- **Modern User Interface:** A clean, responsive, and visually appealing design with a focus on user experience.
- **Interactive Course Management:** Lecturers have a dedicated dashboard to create and manage their course content.
- **Progress Tracking:** Students can easily view their progress through courses and sections.
- **Sample Content:** The platform comes pre-loaded with sample cybersecurity courses to get started right away.

## Getting Started

Follow these steps to get the project up and running on your local machine.

### 1. Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- A database (e.g., MySQL, PostgreSQL, SQLite)

### 2. Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd cybercore
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install NPM dependencies:**
    ```bash
    npm install
    npm run build
    ```

4.  **Set up your environment file:**
    - Copy the example environment file:
      ```bash
      cp .env.example .env
      ```
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```

5.  **Configure your database:**
    - Open the `.env` file and update the `DB_*` variables with your database credentials.
      ```
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=cybercore
      DB_USERNAME=root
      DB_PASSWORD=
      ```

6.  **Run the database migrations and seeders:**
    - This will create the necessary tables and populate the database with default roles, users, and courses.
    ```bash
    php artisan migrate --seed
    ```

7.  **Start the development server:**
    ```bash
    php artisan serve
    ```

You can now access the application at `http://127.0.0.1:8000`.

## Default User Accounts

The database seeder creates two default accounts for you to use:

| Role      | Email                      | Password   |
| :-------- | :------------------------- | :--------- |
| **Admin** | `admin@cybercore.local`    | `anas0807` |
| **Lecturer**| `lecturer@cybercore.local` | `password` |

You can also register a new account, which will have the "student" role by default.

## License

The CyberCore platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).