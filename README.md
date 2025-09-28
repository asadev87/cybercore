# CyberCore E-Learning Platform

CyberCore is a web-based e-learning platform built with the Laravel framework, designed to provide users with courses on cybersecurity topics.

## About The Project

This platform allows users to register, enroll in courses, take quizzes, and earn certificates upon completion. It features a role-based access control system to manage content and users effectively.

## User Roles & Permissions

The application has three primary user roles: Administrator, Lecturer, and Student.

### Administrator
Administrators have full control over the platform. They can manage all aspects of the application, including:
- Creating, editing, and **deleting** any course or module.
- Managing all users and their roles.
- Viewing platform-wide reports and statistics.

**Default Admin Account:**
- **Email:** `admin@cybercore.test`
- **Password:** `password`

### Lecturer
Lecturers are content creators. They have limited permissions focused on managing their own courses.
- Can create new courses.
- Can only edit and manage the courses that they have created.
- **Cannot** delete courses (even their own).
- **Cannot** view platform-wide reports.

**Default Lecturer Account:**
- **Email:** `lecturer@cybercore.test`
- **Password:** `password`

### Student
Students are the primary consumers of the content. Their access is limited to learning activities.
- Can register and enroll in available courses.
- Can take quizzes and view their performance.
- Can earn and view certificates for completed courses.

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).