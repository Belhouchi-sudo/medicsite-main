# Medical Clinic - Appointment System

Setup steps:

1. Import the SQL schema located at `php/sql/create_db.sql` into your MySQL server to create the `medical_clinic` database and tables.

2. Edit database credentials in `php/connection.php` (host, user, password) to match your environment.

3. Ensure the webserver can write to `php/uploads` (the folder will be created automatically on first upload).

4. Access the site via your local webserver (e.g., http://localhost/medicsite-main/). Use `php/login.php` to register or login.

Files added:
- `php/connection.php` - PDO connection
- `php/process_appointment.php` - appointment form processing and file upload
- `php/register.php`, `php/login.php`, `php/logout.php` - authentication
- `php/admin/*` - dashboard and appointment management pages
- `php/sql/create_db.sql` - database creation script
