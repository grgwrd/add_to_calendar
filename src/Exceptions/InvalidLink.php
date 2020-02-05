<?php

namespace Drupal\add_to_calendar\Exceptions;

use DateTime;
use Exception;

class InvalidLink extends Exception
{
    public static function invalidDateRange(DateTime $to, DateTime $from)
    {
        return new self("`{$to->format('YMD His')}` must be greater than `{$from->format('YMD His')}`");
    }
}
