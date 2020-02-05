<?php

namespace Drupal\add_to_calendar\Entity;

interface Generator
{
    public function generate(LinkCal $link);
}
