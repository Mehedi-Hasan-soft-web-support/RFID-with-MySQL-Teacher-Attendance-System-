# Teacher Portal Dashboard - Mehedi's IoT Academy
<img width="1365" height="721" alt="image" src="https://github.com/user-attachments/assets/22333765-d360-4f0e-a997-62691a5a675f" />


![photo_6136229766345377418_y](https://github.com/user-attachments/assets/1ab287a5-bed8-4433-95d5-cac566f36ac5)




## Overview
The Teacher Portal Dashboard is a **web-based academic management system** designed to streamline administrative tasks for teachers and administrators. Built primarily on **MySQL** with PHP backend and a responsive frontend, the system integrates **IoT technology** (ESP32 + RFID) to automate attendance recording and enhance efficiency.

The platform allows managing student information, attendance, marks, notices, and reports from a **single centralized dashboard**, reducing manual work and ensuring accurate, real-time data.

---

## Features
- **Student Management:** Add, update, and view student records.
- **Attendance Automation:** Use RFID cards with ESP32 to record attendance in real time.
- **Marks Management:** Enter and update student marks by subject.
- **Notices & Announcements:** Publish and manage academy notices.
- **Reports:** Generate detailed reports on students, attendance, and academic performance.
- **Faculty Profile Management:** Manage teacher profiles and login credentials.
- **Secure Access:** Role-based login system to protect sensitive data.
- **Responsive Dashboard:** Works on desktops, tablets, and mobile devices.

---

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **IoT Integration:** ESP32 Microcontroller, RFID Reader
- **Server:** Localhost / XAMPP

---

## Project Structure
Teacher-Portal/
├── index.php # Login page
├── welcom.php # Dashboard homepage
├── students.php # Student management
├── marks.php # Marks entry and updates
├── attendance.php # Attendance records
├── work_panel.php # Work panel tasks
├── notices.php # Notices and announcements
├── reports.php # Reports generation
├── profile.php # Teacher profile management
├── create_notice.php # Add new notices
├── assets/
│ ├── css/ # Stylesheets
│ └── js/ # JavaScript files
└── README.md # Project documentation
