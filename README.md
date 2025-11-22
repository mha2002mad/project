# project setup
1. #### run "composer install"
2. #### copy .env.example to .env and provide your database credentials and LOW_STOCK_REPORT_EMAIL in .env, use an empty DB or skip 5
3. #### run "php artisan JWT:secret"
4. #### run "php artisan key:generate"
5. #### run "php artisan migrate:fresh --seed"

#### enter "php artisan test" to run the tests

#### before testing the API, register via "/api/register" and copy the JWT token and use it in form of Authorization: Bearer {token} in other endpoints

#### please refer to "http://basepath:port/docs" to see scribe API documentation. use queryparams with get requests and bodyparams with post requests

#### please find the postman collection in the base path, before using it create an environment for it

- #### run php artisan inventory:check_low_stock to see low stock products
- #### run php artisan mail:daily-email-of-low-stock to trigger the email(seen in storage/logs/laravel.log) and it self-triggers every day at 12:00

