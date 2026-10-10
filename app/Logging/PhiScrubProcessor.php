<?php

namespace App\Logging;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Strips PHI / PII / credential fields from every Monolog record before it is
 * written to disk. Prevents sensitive hearing and personal data from appearing
 * in logs even when APP_DEBUG=true. HIPAA compliance requirement.
 */
class PhiScrubProcessor implements ProcessorInterface
{
    private const SCRUB_KEYS = [
        'password', 'password_confirmation',
        'token', 'access_token', 'refresh_token', 'secret',
        'notes',          // hearing test / session notes
        'email',
        'name',           // scrub in error context
        'google_id',
        'ssn', 'dob', 'date_of_birth',
    ];

    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $this->scrub($record->context);
        $extra   = $this->scrub($record->extra);

        return $record->with(context: $context, extra: $extra);
    }

    private function scrub(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string) $key), self::SCRUB_KEYS, true)) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = $this->scrub($value);
            }
        }

        return $data;
    }
}
