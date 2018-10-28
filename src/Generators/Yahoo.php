<?php

namespace Drupal\add_to_calendar_field\Generators;

use Drupal\add_to_calendar_field\Entity\LinkCal;
use Drupal\add_to_calendar_field\Entity\Generator;

class Yahoo implements Generator
{
    public function generate(LinkCal $link)
    {
        $url = 'https://calendar.yahoo.com/?v=60&view=d&type=20';

        $url .= '&title='.urlencode($link->title);
        $url .= '&st='.$link->from->format('Ymd\THis');
        $url .= '&et='.$link->to->format('Ymd\THis');

        if ($link->description) {
            $url .= '&desc='.urlencode($link->description);
        }

        if ($link->address) {
            $url .= '&in_loc='.urlencode($link->address);
        }

        return $url;
    }
}
