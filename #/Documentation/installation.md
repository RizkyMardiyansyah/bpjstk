# How to run on your machine

## Requirement
- Git
- PHP 7.4 and its extensions
- Web Server (Apache, XAMPP, Nginx)
- MySQL Server
- Composer
- Intermediate Knowledge of Sysadmin
- Intermediate Knowledge of Linux
- Basic Knowledge of Git
- Basic Knowledge of MySQL

## Target Audience
- Developer
- Sysadmin


## Goal
- Run the app on your local machine Windows or Linux
- Allow for developer to run the app on their machine
- Allow for system admin to deploy on production
- Optimizing the app for production
- Troubleshooting common issues during installation

## Non Goal
- Explain how to use Git, changing directory, etc
- Explaining how to download and install the Requirement
- Making sure this tutorial work everywhere
- Debugging the app
- Reproducible step-by-step tutorial, this tutorial may require some additional step not mentioned here
- Every single step is not explained in detail, e.g. creating database, setting directory to root project, etc
- Your environment might be different from author's environment, so you might need to do some additional step

## Setup Requirement on Windows
- Install XAMPP with **PHP 7.4**
- Install Git
- Install Composer


## Setup Requirement on Linux
Note: Add the necessary apt repository
```bash
sudo apt install -y apache2 git mariadb-server
sudo apt install -y php7.4 php7.4-bcmath php7.4-ctype php7.4-curl php7.4-exif php7.4-fileinfo php7.4-gd php7.4-gmp php7.4-iconv php7.4-json php7.4-mbstring php7.4-opcache php7.4-phar php7.4-xml php7.4-xmlreader php7.4-xmlwriter php7.4-zip php7.4-mysql
sudo apt install -y php7.4-cli unzip
# Install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Setup apache
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod expires
sudo systemctl restart apache2
```

- set webserver root document to `/var/www/html/certitude-project-web/public`
```apacheconf
#/etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/certitude-project-web/public
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

- set `/etc/apache2/apache2.conf` to allow `.htaccess`
```apacheconf
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All 
        Require all granted 
</Directory>
```
- `systemctl restart apache2`


## Clone/Download
- Note: we use branch `main` for production, and `base` for development

On Linux:
```bash
cd /var/www/html
git clone -b main https://github.com/beranidigital/certitude-project-web
```
On Windows:
```cmd
cd /d C:\xampp\htdocs
git clone -b main https://github.com/beranidigital/certitude-project-web
```

## Create Database & User (Optional)
```mysql
# Create database
CREATE DATABASE certitude;
# Create user
CREATE USER 'certitude_user'@'localhost' IDENTIFIED BY 'certitude_password';
GRANT ALL PRIVILEGES ON certitude.* TO 'certitude_user'@'localhost';
FLUSH PRIVILEGES;
```
- Username: `certitude_user`
- Password: `certitude_password`
- Host: `localhost`
- Database: `certitude`


## Database Migration
- Go to `#/Databases `and choose between `demo_db.sql` or `empty_db.sql`
- We choose `demo_db.sql` for testing purpose
- Import the database using terminal or using `phpmyadmin` import feature
```bash
mysql -u root -p certitude < \#/Databases/demo_db.sql
php artisan migrate
```



## Setup .env
- Copy `.env.example` to `.env`
- Change the database configuration to your database configuration
```dotenv
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=certitude
DB_USERNAME=certitude_user
DB_PASSWORD=certitude_password
```
- for production usage set
```dotenv
APP_ENV=production
APP_DEBUG=false
```

## Composer Install & Vendor
Still using terminal, change dir to `certitude-project-web`, make sure `composer` available in PATH
```
composer install
```
```bash
php artisan vendor:publish --tag=lfm_config
php artisan vendor:publish --tag=lfm_public
php artisan storage:link
```

- Publish all vendor to update
- `php artisan vendor:publish --all`

## Additional step
- Recommended to run this command on production

```bash
# Regenerate key for production, one time only
php artisan key:generate
# Regenerate JWT Private Key on production, one time only
php artisan jwt:secret
# Regenerate cache every update
# Recommend to cache to prevent compilation failure when high load
php artisan view:clear
php artisan cache:clear
php artisan view:cache

```
## Test The App
- Should look like this
- ![image](https://user-images.githubusercontent.com/77704356/248282220-4c8c668e-891a-415e-b20e-f5c9ef5c65f4.png)


## Troubleshooting
- set `APP_DEBUG=true` in `.env` to see the error
- check the apache log `/var/log/apache2/error.log`


### Storage Permission Error
- Most common on Linux, you will see this if you set ENV to local
   - ```log
     The stream or file "/var/www/html/certitude-project-web/storage/logs/laravel.log" 
     could not be opened in append mode: failed to open stream: 
     Permission denied The exception occurred while attempting to lo
     ```
  - ```log
     file_put_contents(/var/www/html/certitude-project-web/storage/framework/sessions/t1NfYpGJBC0aQZ7MnGg2mSeCMVbuzuTdwscNeyYH): 
     failed to open stream: Permission denied
     ```

- Solution: Give permission to the storage folder
   - ```bash
     sudo chmod o+w ./storage/ -R
     sudo chown www-data:www-data ./storage/ -R
     sudo chown www-data:www-data ./public/store/ -R
     ```
   - Doesn't work ? give more
     ```bash
     sudo chmod -R gu+w storage
     sudo chmod -R guo+w storage
     sudo find . -type f -exec chmod 664 {} \;   
     sudo find . -type d -exec chmod 775 {} \;
     php artisan clear:all
     ```

### 404 Not Found, the requested url was not found on this server
- Most common on Linux
- Solution: Enable `rewrite` module
    - ```bash
      sudo a2enmod rewrite
      sudo systemctl restart apache2
      ```
    - set `/etc/apache2/apache2.conf` to allow `.htaccess`
      ```apache
      <Directory /var/www/>
              Options Indexes FollowSymLinks
              AllowOverride All 
              Require all granted
      </Directory>
      ```
    - https://stackoverflow.com/questions/28242495/laravel-the-requested-url-was-not-found-on-this-server

### 500 Internal Server Error
- Many possible cause
  - `.env` not configured properly
  - Apache config not configured properly
  - composer not installed properly
  
- Solution:
  - set `APP_DEBUG=true` and `APP_ENV=local` in `.env` to see the error
  - check the apache log `/var/log/apache2/error.log`
  - `composer install` again
  - there no definitive solution for this, since it's a generic error


### File failed to upload

- "Laravel file manager says [ object object ] when trying to upload the image to the server"
- "422 Unprocessable Entity"

![image](https://github.com/beranidigital/certitude-project-web/assets/77704356/39d39589-e19a-4730-99da-4fd0ba2124f3)

- Possible cause:
  - File size too big
  - Storage permission error

- Solution:
  - edit `php.ini` and set `upload_max_filesize` and `post_max_size` to bigger value
  - `upload_max_filesize = 500MB` and `post_max_size = 5MB` on `php.ini`
