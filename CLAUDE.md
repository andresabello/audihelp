# Audihelp Backend — Architecture Guide

## Overview
Laravel 13 modular-monolith REST API for the Personalized Hearing Assistance MVP. No frontend in this repo — Android/Kotlin app is a separate project.

## Tech Stack
- PHP 8.4 / Laravel 13
- PostgreSQL 16 (primary), Redis 7 (sessions/cache/queue)
- Laravel Passport 13 (OAuth2 — Google login only)
- Laravel Socialite (Google OAuth flow)
- Dedoc Scramble (OpenAPI docs at `/docs/api`)
- Pest 4 (tests)

## Module Structure
```
app/Modules/
├── Accounts/        — Google OAuth auth, Passport token issuance
├── HearingTests/    — Test sessions + per-frequency tone trials
├── Profiles/        — Saved hearing threshold curves
├── DspPresets/      — DSP configuration presets (JSONB config)
└── LiveSessions/    — Live voice processing session logs
```

Each module owns: Models, Http/{Controllers,Requests,Resources}, Services, Enums, routes.php, *ServiceProvider.php

Module providers are registered in `bootstrap/app.php → withProviders([...])`.

## HIPAA Compliance
- PHI fields encrypted at rest using Laravel's `'encrypted'` cast: `notes` on sessions, profiles, live sessions
- `app/Logging/PhiScrubProcessor.php` scrubs sensitive keys from every log record (registered in AppServiceProvider)
- `app/Models/AuditLog.php` records auth events and data mutations — call `AuditLog::record(action, user, ...)`

## Auth
- Google OAuth only — `GET /api/v1/auth/google` → redirect → `GET /api/v1/auth/google/callback` → Passport token
- All domain routes protected by `auth:api` middleware
- No password-based auth

## API Shape
- Base prefix: `/api/v1/`
- Responses use Laravel API Resources (`data` key wrapping)
- Errors: JSON with `message` key, HTTP status code

## Database
- All primary keys: UUID (`HasUuids` trait)
- Soft deletes on User, HearingTestSession, HearingProfile, DspPreset, LiveSession
- JSONB for `dsp_presets.config`
- No integer auto-increment PKs

## Running Locally
```bash
cp .env.example .env
# Fill in GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, APP_KEY
php artisan key:generate
docker compose up -d
php artisan migrate
php artisan passport:install
```

## Tests
```bash
php artisan test
# or
./vendor/bin/pest
```

## API Docs
Visit `/docs/api` when the server is running (Scramble auto-generates OpenAPI 3.1).

## DSP Preset Config Shape
```json
{
  "eq": [{ "freq": 1000, "gain_db": 6, "q": 1.4, "type": "peak" }],
  "compressor": { "threshold_dbfs": -20, "ratio": 4, "attack_ms": 10, "release_ms": 100 },
  "limiter": { "ceiling_dbfs": -3 },
  "freq_transform": { "enabled": false, "shift_hz": 0 },
  "modulation": { "enabled": false, "depth": 0, "rate_hz": 0 },
  "gain_db": 0
}
```
