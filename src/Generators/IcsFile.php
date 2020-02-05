<?php

namespace Drupal\add_to_calendar\Generators;


use Drupal\add_to_calendar\Entity\LinkCal;
use Drupal\add_to_calendar\Entity\Generator;

class IcsFile implements Generator
{
    public function generate(LinkCal $link)
    {
        $url = ['BEGIN:VCALENDAR',
            'VERSION:2.0',
            'BEGIN:VEVENT',
            'DTSTART:'.$link->from->format('Ymd\THis'),
            'DTEND:'.$link->to->format('Ymd\THis'),
            'SUMMARY:'.$link->title, ];

        if ($link->description) {
            $url[] = 'DESCRIPTION:'.addcslashes($link->description, "\n");
        }
        if ($link->address) {
            $url[] = 'LOCATION:'.str_replace(',', '', $link->address);
        }

        $url[] = 'END:VEVENT';
        $url[] = 'END:VCALENDAR';
        $icsFile = implode("\r\n", $url);


        return $icsFile;
    }

}
