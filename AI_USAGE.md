# AI Usage

## Tools used
- Google Gemini web and CLI

## Where they were helpful
- For generating most of the codes

## Things they got wrong or incomplete
- Gemini insisted in specifying app specific env vars (DB_*) in `docker-compose.yml` file. I told it to remove those from `docker-compose.yml` file and let `.env` handle it. Otherwise, it will be an issue when running automated tests. But it still kept the env vars and just made the values dynamic (e.g. using `${VAR}`).
- The nginx `default.conf` file is incomplete. It doesn't specify the `server` block.
- It created a repository (as in a data layer repository) and covered it with tests even though it's not being used anywhere.
- It used the `Referral` model to store the idempotency key and used a [State Processor](https://api-platform.com/docs/core/state-processors/) to handle idempotency. 

## Things I manually verified/changed
- The `docker-compose.yml` file due to the issue above.
- I used the nginx `default.conf` file that Laravel mentioned in their documentation.
- I changed the way it handled idempotency to use a middleware that uses Redis to store idempotency key with 24 hour TTL.
