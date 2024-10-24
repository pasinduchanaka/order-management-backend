# Laravel Project Setup with Docker

This guide will help you clone the Laravel project and set it up using Docker.

## Prerequisites

Before proceeding, ensure you have the following installed on your system:

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)

## Step-by-Step Setup

### 1. Clone the Repository

First, clone the repository to your local machine:

```bash
git clone git@github.com:pasinduchanaka/order-management-backend.git
```
### 2. Set Up Environment Variables

Duplicate the .env.example file to create the .env file and modify it as needed:

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=order_management_system
DB_USERNAME=sail
DB_PASSWORD=password

WWWUSER = 1000
WWWGROUP = 1000
```
### 3. Installing Composer Dependencies for Application

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
### 4. Run Docker Containers

``` docker compose up```

### 5. Run Key Generator, Migrations and Seed
First run ```docker ps``` to see if the containers are running:

This will display the running containers along with their IDs.

Next, access the PHP container by running: ```docker exec -it <container_id> /bin/bash```

Once inside the container, run the following commands: ```php artisan key:generate``` and 

```php artisan migrate```


Then, seed the database using: ```php artisan db:seed --class=DatabaseSeeder```

Now the backend is ready to be used. URL to access the backend is: http://localhost:8010




# Laravel Project Setup using PHP Artisan Serve

This guide will help you clone the Laravel project and run it using `php artisan serve`.

## Prerequisites

Before proceeding, ensure you have the following installed on your system:

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) (version 8.0 or higher)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)

## Step-by-Step Setup

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone git@github.com:pasinduchanaka/order-management-backend.git
```

### 2. Install Dependencies

Install the required PHP dependencies using Composer: ```composer install```

### 3. Set Up Environment Variables

Duplicate the .env.example file to create the .env file and modify it as needed:

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=order_management_system
DB_USERNAME=sail
DB_PASSWORD=password
```

### 4. Generate the Application Key
Generate the Laravel application key:   ```php artisan key:generate```

### 5. Set Up the Database
Create your database in MySQL (or any other DB you're using) based on the name specified in your .env file.

Then, run the migrations to create the necessary tables: ```php artisan migrate```

### 6. Set Up the Database
Create your database in MySQL (or any other DB you're using) based on the name specified in your .env file.

Then, run the migrations to create the necessary tables: ```php artisan migrate```

Then run the seeders to create the necessary data: ```php artisan db:seed --class=DatabaseSeeder```


### 6. Run the Laravel Development Server
You can now start the Laravel development server by running: ```php artisan serve```

Visit http://localhost:8000 in your browser to access the application.
