# Railway Ticket Booking System

A PHP and MySQL based Railway Ticket Booking System for searching trains, checking seat availability, booking tickets, viewing booked tickets, checking PNR status, cancelling tickets, and managing trains from an admin panel.

This project is built with plain PHP, MySQL/MariaDB, HTML, CSS, JavaScript, Bootstrap, and Font Awesome. It is designed to run locally with XAMPP.

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Database Setup](#database-setup)
- [How to Run the Project](#how-to-run-the-project)
- [Default Login Details](#default-login-details)
- [User Workflow](#user-workflow)
- [Admin Workflow](#admin-workflow)
- [Real-Time Data Updates](#real-time-data-updates)
- [Important Database Tables](#important-database-tables)
- [Useful Test Route](#useful-test-route)
- [Troubleshooting](#troubleshooting)
- [Notes](#notes)

## Features

### User Features

- User registration
- User login and logout
- Search trains by source, destination, class, and date
- Check available seats by train number
- Book train tickets
- Add passenger details
- Review ticket before payment
- Payment form flow
- View booked tickets from the "My Tickets" page
- Check PNR status
- Cancel booked tickets
- Contact/feedback form
- Live seat availability refresh on the seat availability page

### Admin Features

- Admin login and logout
- Admin dashboard
- View trains
- Add train details
- Edit train details
- Delete train details
- View all tickets
- View confirmed tickets by train number
- Check PNR status
- View feedback/contact messages
- Fare report
- Run safe custom SELECT queries

## Technology Stack

- Frontend: HTML, CSS, Bootstrap, JavaScript
- Backend: PHP
- Database: MySQL/MariaDB
- Local server: XAMPP or PHP built-in server
- Database UI: phpMyAdmin

## Project Structure

```text
Railway-Ticket-Booking-System/
  asset/
    css/
    database/
      traindb.sql
    font-awesome/
    img/
    js/
  DBConnection.php
  index.php
  login.php
  register.php
  train_list.php
  check_seats.php
  psg_details.php
  review.php
  payment.php
  my_tickets.php
  pnrstatus.php
  seat_status.php
  Adminlogin.php
  admin_dashboard.php
  admin_addtrains.php
  admin_edittrains.php
  admin_deletetrains.php
  admin_viewtrains.php
  admin_viewtickets.php
  confirmed_tickets.php
  fair_report.php
  custom_query.php
```

## Requirements

Install the following before running the project:

- XAMPP
- PHP 7.4 or newer
- MySQL/MariaDB
- Web browser
- Git, if cloning from GitHub

This project was tested locally using:

- XAMPP PHP
- XAMPP MariaDB on port `3307`
- phpMyAdmin

## Database Setup

### 1. Start XAMPP

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

If MySQL uses port `3307`, keep the project database connection as it is.

### 2. Open phpMyAdmin

Open:

```text
http://localhost/phpmyadmin
```

### 3. Create Database

Create a database named:

```text
traindb
```

### 4. Import SQL File

Import this file into the `traindb` database:

```text
asset/database/traindb.sql
```

### 5. Check Database Connection

The database connection is configured in:

```text
DBConnection.php
```

Current configuration:

```php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "traindb";
```

If your MySQL runs on the default port `3306`, change it to:

```php
$servername = "127.0.0.1:3306";
```

or:

```php
$servername = "localhost";
```

## How to Run the Project

You can run the project in either of these ways.

### Option 1: Run with PHP Built-In Server

Open PowerShell or Command Prompt inside the project folder:

```powershell
cd path\to\Railway-Ticket-Booking-System
C:\xampp\php\php.exe -S 127.0.0.1:8000
```

Then open:

```text
http://127.0.0.1:8000/index.php
```

### Option 2: Run through XAMPP htdocs

Copy the project folder into:

```text
C:\xampp\htdocs\
```

Example:

```text
C:\xampp\htdocs\Railway-Ticket-Booking-System
```

Start Apache and MySQL from XAMPP, then open:

```text
http://localhost/Railway-Ticket-Booking-System/index.php
```

## Default Login Details

### User Login

```text
Username: abcd
Password: abcd
```

Other imported users may also exist in the database.

### Admin Login

```text
Username: a
Password: a
```

Admin login page:

```text
http://127.0.0.1:8000/Adminlogin.php
```

## User Workflow

### 1. Register or Login

Open:

```text
http://127.0.0.1:8000/index.php
```

Login using an existing user or create a new account from the Register page.

### 2. Search Train

Use this sample route:

```text
From: Mumbai
To: Ahmedabad
Class: ALL
Date: any date
```

Click:

```text
Find Trains
```

### 3. Book Ticket

On the train list page, click:

```text
Book Now
```

Then enter passenger details:

```text
Name
Age
Gender
Mobile number
```

Click:

```text
Continue
```

### 4. Review Ticket

The review page displays:

- Train name
- Train number
- Source
- Destination
- Journey date
- Passenger details
- PNR number
- Fare

Click Continue to open the payment page.

### 5. Payment Page

Enter test card details in the payment form. The project validates the form fields locally.

Example:

```text
Card Number: 1234567812345678
Expiry Date: any future date
CVV: 123
Name on Card: Test User
```

### 6. View Booked Tickets

After login, open:

```text
http://127.0.0.1:8000/my_tickets.php
```

Or use:

```text
Trains -> My Tickets
```

### 7. Check PNR Status

Open:

```text
http://127.0.0.1:8000/pnrstatus.php
```

Enter the PNR number and click:

```text
Get Status
```

### 8. Cancel Ticket

Open the PNR status page, search the PNR, then click:

```text
Cancel
```

When cancelled:

- `ticket.status` changes to `cancelled`
- `train.seat_avail` increases

## Admin Workflow

### 1. Admin Login

Open:

```text
http://127.0.0.1:8000/Adminlogin.php
```

Login with:

```text
Username: a
Password: a
```

### 2. Admin Dashboard

After login, admin can access:

```text
admin_dashboard.php
```

### 3. Manage Trains

Admin can:

- View trains
- Add train details
- Edit train details
- Delete train details

Pages:

```text
admin_viewtrains.php
admin_addtrains.php
admin_edittrains.php
admin_deletetrains.php
```

### 4. Manage Tickets

Admin can view all tickets:

```text
admin_viewtickets.php
```

Admin can view confirmed tickets by train number:

```text
confirmed_tickets.php
```

### 5. Reports and Queries

Fare report:

```text
fair_report.php
```

Custom SELECT query page:

```text
custom_query.php
```

Only `SELECT` queries are allowed on the custom query page.

## Real-Time Data Updates

The project stores all booking data in MySQL/MariaDB.

When a ticket is booked:

- A new row is inserted into `ticket`
- Passenger rows are inserted into `passanger`
- Seat count decreases in `train.seat_avail`

When a ticket is cancelled:

- `ticket.status` is updated to `cancelled`
- Seat count increases in `train.seat_avail`

The seat availability page uses:

```text
seat_status.php
```

It refreshes available seat count every 5 seconds using JavaScript `fetch()`.

You can also verify live data in phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Check:

```text
traindb -> ticket
traindb -> passanger
traindb -> train
```

## Important Database Tables

### `user`

Stores user account details.

### `admin`

Stores admin login details.

### `train`

Stores train number, train name, class, and available seats.

### `station`

Stores source, destination, fare, arrival time, departure time, duration, and train number.

### `ticket`

Stores ticket number, status, journey date, phone, email, train number, station number, and username.

### `passanger`

Stores passenger name, age, gender, seat number, and ticket number.

### `contact`

Stores contact/feedback form messages.

## Useful Test Route

Use this route to test booking quickly:

```text
From: Mumbai
To: Ahmedabad
Class: ALL
Train No: 12267
```

Seat availability page:

```text
http://127.0.0.1:8000/check_seats.php?train_id=12267
```

## Troubleshooting

### phpMyAdmin does not open

Start Apache from XAMPP Control Panel.

Then open:

```text
http://localhost/phpmyadmin
```

### Database connection failed

Check:

```text
DBConnection.php
```

Make sure the port matches your MySQL/MariaDB port.

Common options:

```php
$servername = "127.0.0.1:3307";
```

or:

```php
$servername = "localhost";
```

### No trains appear in search

Use a route that exists in the imported database:

```text
Mumbai -> Ahmedabad
Aurangabad -> Secunderabad
Madurai -> Chennai
Jammu -> Delhi
NewDelhi -> Lucknow
Ludhiana -> Delhi
Aurangabad -> Mumbai
```

### Book Now button does not appear

Make sure:

- You are logged in
- The train has seats available
- `train.seat_avail` is greater than `0`

### Admin pages redirect to login

Login from:

```text
Adminlogin.php
```

Use:

```text
Username: a
Password: a
```

### Port 8000 already in use

Run with a different port:

```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8001
```

Then open:

```text
http://127.0.0.1:8001/index.php
```

## Notes

- This is an academic/local project.
- Passwords are stored in plain text in the imported database.
- Payment is a demo form and does not connect to a real payment gateway.
- For production use, add password hashing, prepared SQL statements, CSRF protection, validation, and proper authentication controls.

