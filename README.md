# 🏥 MedHBook – Medical History Book & Appointment Booking System

MedHBook is a web-based healthcare management system designed to securely manage patients' medical histories and simplify doctor appointment booking.

The system allows patients to maintain their medical records, search for doctors, book appointments, and securely share medical information with authorized doctors.

---

## 📌 Project Overview

MedHBook combines two major healthcare functionalities:

* 📋 Digital Medical History Management
* 📅 Doctor Appointment Booking

The system focuses on improving healthcare accessibility, reducing paperwork, preventing appointment conflicts, and protecting sensitive medical information.

---

## ✨ Key Features

### 👤 Patient

* Patient registration and login
* Manage personal profile
* Maintain digital medical history
* Upload medical documents
* View previous medical records
* Search and view available doctors
* Book doctor appointments
* View appointment status
* Cancel appointments
* Manage booked appointments
* Securely share medical records with doctors

### 👨‍⚕️ Doctor

* Doctor registration and login
* Manage professional profile
* Set availability and appointment slots
* View appointment requests
* Accept or reject appointments
* View patient information
* Access authorized medical records
* Manage patient appointments

### 🛡️ Admin

* Admin authentication
* Manage patients
* Manage doctors
* Manage appointments
* Monitor system activities
* Manage users and system information

---

## 🔐 Security & Privacy

MedHBook is designed with patient privacy as a major priority.

* Secure authentication
* Role-based access control
* Protected medical records
* Encrypted uploaded medical documents
* Privacy-key based medical record access
* Authorized doctor-only access to patient records
* Secure database communication
* Input validation and protected routes

---

## 📅 Appointment Booking System

The appointment module allows patients to book doctors based on available time slots.

### Appointment Workflow

1. Patient searches for a doctor
2. Patient views available appointment slots
3. Patient selects a suitable date and time
4. Appointment request is submitted
5. Doctor reviews the request
6. Doctor accepts or rejects the appointment
7. Patient can view the appointment status

The system checks for slot conflicts to prevent multiple bookings for the same appointment time.

---

## 🏗️ System Architecture

```text
                    ┌─────────────────────┐
                    │      Frontend       │
                    │      React.js       │
                    └──────────┬──────────┘
                               │
                               │ API Requests
                               ▼
                    ┌─────────────────────┐
                    │       Backend       │
                    │   Laravel / PHP     │
                    └──────────┬──────────┘
                               │
                ┌──────────────┴──────────────┐
                │                             │
                ▼                             ▼
       ┌─────────────────┐          ┌─────────────────┐
       │     MySQL       │          │ Medical Files   │
       │    Database     │          │   & Documents   │
       └─────────────────┘          └─────────────────┘
```

---

## 🛠️ Technologies Used

### Frontend

* React.js
* HTML5
* CSS3
* JavaScript

### Backend

* PHP
* Laravel

### Database

* MySQL

### Other Technologies

* REST API
* Authentication & Authorization
* File Upload
* Encryption
* Role-Based Access Control

---

## 👥 User Roles

| Role    | Main Responsibilities                          |
| ------- | ---------------------------------------------- |
| Patient | Medical history, appointments, profile         |
| Doctor  | Appointments, patient records, availability    |
| Admin   | Users, doctors, patients and system management |

---

## 📂 Main Modules

```text
MedHBook
│
├── Authentication
├── Patient Management
├── Doctor Management
├── Medical History
├── Medical Document Management
├── Doctor Search
├── Appointment Booking
├── Appointment Management
├── Privacy & Authorization
└── Admin Management
```

---

## 🔄 Medical Record Access Flow

```text
Patient
   │
   │ Upload Medical Record
   ▼
Secure Storage
   │
   │ Authorization / Privacy Key
   ▼
Authorized Doctor
   │
   ▼
View Patient Medical History
```

Doctors cannot freely access a patient's complete medical history. Medical information is protected and can only be accessed through the appropriate authorization mechanism.

---

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/rajmukut791/MedHBook.git
```

### 2. Navigate to the Project

```bash
cd MedHBook
```

### 3. Install Backend Dependencies

```bash
composer install
```

### 4. Configure Environment

Create a `.env` file:

```bash
cp .env.example .env
```

Update the database configuration:

```env
DB_DATABASE=medhbook
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migration

```bash
php artisan migrate
```

### 7. Start Laravel Server

```bash
php artisan serve
```

---

## 💻 Frontend Setup

Navigate to the frontend directory:

```bash
cd frontend
```

Install dependencies:

```bash
npm install
```

Start the development server:

```bash
npm run dev
```

---

## 📸 Screenshots

```markdown
![Login Page](screenshots/login.png)

![Patient Dashboard](screenshots/patient-dashboard.png)

![Doctor Dashboard](screenshots/doctor-dashboard.png)

![Appointment Booking](screenshots/appointment-booking.png)

![Medical History](screenshots/medical-history.png)
```

---

## 🎯 Project Objectives

* Digitize traditional medical history management
* Reduce paperwork in healthcare services
* Simplify doctor appointment booking
* Prevent appointment slot conflicts
* Improve communication between patients and doctors
* Protect sensitive medical information
* Provide controlled access to medical records
* Create a centralized healthcare management platform

---

## 🔮 Future Improvements

* Online doctor consultation
* Video consultation
* Online payment integration
* Email/SMS appointment notifications
* Prescription management
* Medicine reminders
* Advanced medical analytics
* Mobile application
* AI-assisted health recommendations
* Multi-hospital support

---

## 📚 Academic Project

**Project Name:** MedHBook
**Full Form:** Medical History Book & Appointment Booking
**Project Type:** Healthcare Management System
**Field:** Computer Science & Engineering

---

## 👨‍💻 Developer

**Raj Mukut**

Computer Science & Engineering
Northern University Bangladesh

### Connect

* GitHub: https://github.com/rajmukut791
* Facebook: https://facebook.com/rajmukut791
* Email: [rajmukut791@gmail.com](mailto:rajmukut791@gmail.com)

---

## 📄 License

This project was developed for academic and educational purposes.

© 2026 Raj Mukut. All Rights Reserved.
