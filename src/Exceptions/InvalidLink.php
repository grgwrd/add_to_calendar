<?php

namespace Drupal\add_to_calendar_field\Exceptions;

use DateTime;
use Exception;

class InvalidLink extends Exception
{
    public static function invalidDateRange(DateTime $to, DateTime $from)
    {
        return new self("`{$to->format('YMD His')}` must be greater than `{$from->format('YMD His')}`");
    }
}
