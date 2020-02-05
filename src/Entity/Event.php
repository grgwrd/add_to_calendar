<?php

namespace Drupal\add_to_calendar\Entity;
use \Datetime;
use \Datetimezone;

use \Drupal\node\Entity\Node;

class Event
{
    //fields from view replacement pattern
    protected $title;

    protected $from;

    protected $to;

    protected $location;

    protected $description;

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function __construct($title, $from, $to, $description, $location)
    {
      $this->title = $title;
      $this->description = $description;
      $this->location = $location;

      $this->setFrom($from);
      $this->setTo($to);
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
      return $this->location;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
      return $this->title;
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @return mixed
     */
    public function getTo()
    {
      return $this->to;
    }
    /**
     * @return mixed
     */
    public function getFrom()
    {
      return $this->from;
    }
    /**
     * @param mixed $endDate
     */
    public function setFrom($from)
    {

      str_replace('T', ' ', $from);
//
//      date_default_timezone_set("UTC");

      $timezone = new DateTimeZone("UTC");
      //$timezone = new DateTimeZone('America/Chicago');


      try {
        $from = new Datetime($from);
        date_default_timezone_get();
        $from->setTimezone($timezone);
      } catch (\Exception $e) {
        throwException($e);
      }

      $this->from = $from;
    }

    /**
     * @param mixed $startDate
     */
    public function setTo($to)
    {

      str_replace('T', ' ', $to);

      //date_default_timezone_set("UTC");

      $timezone = new DateTimeZone("UTC");

      try {
        $to = new Datetime($to, $timezone);
        date_default_timezone_get();
        $to->setTimezone($timezone);
      } catch (\Exception $e) {
        throwException($e);
      }

      $this->to = $to;
    }



}
