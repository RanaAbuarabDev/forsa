# WorkNetSYR Backend (فرصة)

WorkNetSYR is a RESTful backend API built with Laravel that aims to bridge the employment gap in Syria by connecting job seekers, professionals, freelancers, and employers on a single digital platform.

The system provides a secure and scalable backend for a mobile application, enabling users to publish job opportunities, apply for jobs, manage professional profiles, and receive real-time notifications.

---

## 🚀 Features

- RESTful API architecture
- JWT-based authentication
- OTP verification via SMTP (Email)
- User registration, login, and password recovery
- Professional profile management:
  - Personal information
  - Skills
  - Experiences
  - Educational qualifications
  - Certificates
  - Social links
  - CV upload
- Job posts management (CRUD)
- Apply for jobs
- Save / unsave job posts
- Cancel job applications
- Job filtering based on skills and preferences
- Real-time notifications:
  - Notify employers when a job is booked/applied for
  - Notify users when matching job opportunities are available
- Secure access using policies and middleware
- API Resources for clean and consistent responses
- Postman collection for API documentation

---

## 🛠 Tech Stack

- **Framework:** Laravel 10
- **Language:** PHP
- **Authentication:** JWT
- **Database:** MySQL
- **Mail Service:** SMTP
- **API Documentation:** Postman
- **Architecture:** Service-based architecture

---

## 📂 Project Structure Overview

- `Controllers` – Handle API requests
- `Services` – Business logic layer
- `Requests` – Request validation
- `Resources` – API response formatting
- `Policies` – Authorization logic
- `Notifications` – System notifications
- `Mail` – Email and OTP handling
- `Enums` – Enum definitions
- `Middleware` – Request handling constraints

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- SMTP credentials

### Steps

```bash
git clone https://github.com/RanaAbuarabDev/forsa.git
cd forsa
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
