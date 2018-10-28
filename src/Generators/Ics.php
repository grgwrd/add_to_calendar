<?php

namespace Drupal\add_to_calendar_field\Generators;

use Drupal\add_to_calendar_field\Entity\LinkCal;
use Drupal\add_to_calendar_field\Entity\Generator;

class Ics implements Generator
{
    public function generate(LinkCal $link)
    {
        $beginData = 'data:text/calendar;charset=utf8,';
        $url = ['BEGIN:VCALENDAR',
          'VERSION:2.0',
          'BEGIN:VEVENT',
          'DTSTART:'.$link->from->format('Ymd\THis'),
          'DTEND:'.$link->to->format('Ymd\THis'),
          'SUMMARY:'.$link->title, ];

        if ($link->description) {
            $url[] = 'DESCRIPTION:'.$link->description;
        }
        if ($link->address) {
            $url[] = 'LOCATION:'.str_replace(',', '', $link->address);
        }

        $url[] = 'END:VEVENT';
        $url[] = 'END:VCALENDAR';
        $redirectLink = implode('%0A', $url);

        $redirectLink = $beginData.$redirectLink;

        return $redirectLink;
    }
}
