# Housecall Homework

This homework is about managing referrals

## Prerequisites
- PHP 8.3+ w/ Composer
- Postgres 18
- Redis 8

## Local setup
- Copy `.env.example` to `.env`
- Update the database and cache config in your `.env` 
- And run:
  - `composer install` - to install dependencies
  - `php artisan migrate --seed` - to set up database and create a default user 
  - `php artisan api-token:generate` - to generate an API token for the default user
  - `php artisan queue:work` - to run the queue worker
- To run the tests, run `php artisan test`

## Sample API request
- `curl -XGET -H 'accept:application/json' -H 'autorization:bearer <your default user's token>' http://localhost/api/v1/referrals` - to fetch the referrals collection
- `curl -XPOST -H 'content-type:application/json' -H 'accept:application/json' -H 'autorization:bearer <your default user's token>' -H 'idempotency-key:key1' http://localhost/api/v1/referrals` -d '{{"name":"John Doe","age":40,"address":"Some Address","reason":"Some reason","priority":"low","source":"Some Source"}}' - to create a referral
- `curl -XGET -H 'accept:application/json' -H 'autorization:bearer <your default user's token>' http://localhost/api/v1/referrals/{referral_id}` - to fetch a specific referral
