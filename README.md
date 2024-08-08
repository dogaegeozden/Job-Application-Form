# Application Overview

This is a web application that accepts feedbacks for a fictional company.

## Features:

- Displays server information
- Allows users to submit job application
- Validates input
- Creates folders for applicants and writes input into files

## Technologies Used

- HTML
- CSS
- PHP
- NGINX

## How to Use?

1) Update and upgrade your linux server/machine.

	   sudo apt update && sudo apt upgrade

2) Install dependencies.

	   sudo apt install nginx php-fpm

3) Create a site.

	   sudo nano /etc/nginx/sites-available/job-application-form

4) Set directory permissions.

       chmod 777 -R /home/usernamehere/Desktop/PHP/Exercises/job_application_form

```
server {
    listen 2000;
    root /home/usernamehere/Desktop/PHP/Exercises/job_application_form;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        include fastcgi_params;
    }
}
```

4) Enable the site.

	   ln -s /etc/nginx/sites-available/job-application-form /etc/nginx/sites-enabled

5) Test nginx conf.

	   sudo nginx -t

6) Start, reload and restart nginx

	   sudo systemctl start nginx && sudo systemctl reload nginx && sudo systemctl restart nginx

7) Visit http://localhost:2000 

### Notes

1) This application is for demonstration purposes only and should not be used for production without further development and testing.
2) This app is developed for nginx version: nginx/1.22.1 and php-fpm8.2