<?php

namespace App\Repositories;

use Exception;
use Ramsey\Uuid\Uuid;

class UniqueNameRepository
{
    /**
     * Create unique identifier.
     *
     * @return string
     */
    public static function createIdentifier()
    {
        try {
            return (string) Uuid::uuid4();
        } catch (Exception $exception) {
            // Uuid exception unhandled
        }
    }
}
