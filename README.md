## Installation Steps

- composer install
- cp .env.example .env
- update CACHE_DRIVER = array and database config in .env file
- php artisan migrate
- php artisan migrate --seed
- import postman collection to verify the api => TodoList.postman_collection


