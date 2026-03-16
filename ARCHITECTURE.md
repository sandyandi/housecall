# Architecture

## Key Design Decisions
- I used [API Platform](https://api-platform.com/) due to robustness and familiarity
- Most logic reside in the `\App\Models\Referral` class following SRP and Information Expert principle

## Schema Choices
- I gathered the referrals fields from the requirement
- I indexed the `referrals.status` field for fast lookup on that field

## Queue Job Design
- I implemented a `created` listener in the `\App\Models\Referral` model that dispatches the triage job
- The `\App\Jobs\TriageReferral` job then calls the `\App\Models\Referral::triage` method to do the triaging
- The job retries 5 times before failing

## Auth Approach
- I used Laravel Sanctum for auth

## Tradeoffs
- I could've implemented the `cancel` endpoint and improved the deployment and feature tests given more time

## API Reference
- Please refer to `swagger_docs.json`
