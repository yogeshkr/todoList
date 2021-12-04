## Installation Steps

- composer install
- cp .env.example
- update CACHE_DRIVER = array and database config
- composer dump-autoload
- php artisan migrate
- php artisan migrate --seed
- import postman collection to verify the api => TodoList.postman_collection


