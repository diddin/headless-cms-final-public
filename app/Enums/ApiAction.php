<?php

/**
 * Enum ApiAction
 *
 * Represents various API actions as string values.
 *
 * @package App\Enums
 *
 * @method string toMessageVerb() Converts the enum value to a corresponding message verb.
 *
 * Enum Cases:
 * @constant string LIST Represents the 'list' action.
 * @constant string RETRIEVED Represents the 'retrieved' action.
 * @constant string CREATED Represents the 'created' action.
 * @constant string UPDATED Represents the 'updated' action.
 * @constant string DELETED Represents the 'deleted' action.
 */
namespace App\Enums;

enum ApiAction: string
{
    case LIST = 'list';
    case RETRIEVED = 'retrieved';
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';

    public function toMessageVerb(): string
    {
        return match ($this) {
            self::LIST => 'list retrieved',
            self::RETRIEVED => 'retrieved',
            self::CREATED => 'created',
            self::UPDATED => 'updated',
            self::DELETED => 'deleted',
        };
    }
}
