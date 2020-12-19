# Lumen PHP Framework

## Hotel Booking App Backend
- For GP Codemaster 

## Install 

1) Clone the repository

``` bash
git clone https://github.com/4msar/gp-codemaster.git
```

2) Set your database information in your .env file (use the .env.example as an example);

3) Run these command in project folder:
``` bash
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan db:seed
```

4) After migration done, you can logged in by this credentials  
Email: `admin@admin.com`  
Password: `password`


## Docs
API Docs can be found [here](https://app.swaggerhub.com/apis-docs/msar/GP-Hackathon/1.0.0)