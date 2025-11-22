# project setup
1. #### run "composer install"
2. #### copy .env.example to .env and provide your database credentials and LOW_STOCK_REPORT_EMAIL in .env, use an empty DB or skip 5
3. #### run "php artisan kwt:secret"
4. #### run "php artisan key:generate"
5. #### run "php artisan migrate:fresh --seed"

#### enter "php artisan test" to run the tests

#### when using the API, register via "/api/register" in postman or scribe and copy the JWT token and use it in request Headers in form of Authorization: Bearer {token} in other endpoints and all other endpoint need that header to pass though authentication.

#### refer to "http://basepath:port/docs" to see scribe API documentation. use queryparams with get requests and bodyparams with post requests

#### find the postman collection in the project's base folder, before using it create an environment for it

- #### run "php artisan inventory:check_low_stock" to see low stock products
- #### run "php artisan schedule:list" to see scheduled jobs along with their time frequency.
- #### run "php artisan mail:daily-email-of-low-stock" to trigger the email(seen in storage/logs/laravel.log) and it self-triggers every day at 12:00.
