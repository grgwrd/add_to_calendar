<?php

namespace Drupal\add_to_calendar\Entity;
use \Datetime;
use \Datetimezone;
use DateInterval;
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
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $from = $this->setDatetimeInterval($from);

        $this->from = $from;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
      $to = $this->setDatetimeInterval($to);
      $this->to = $to;
    }

    private function setDatetimeInterval($datetime){

        //replace empty space with T for timezone string
        str_replace('T', ' ', $datetime);

        $eventDatetime = NULL; //return value for function

        //convert time from drupal system datetime to relevant datetime
        try {
            $systemTZ = date_default_timezone_get();

            $drupalDateTime = new DateTimeZone($systemTZ);
            $utcTimezone = new DateTimeZone('UTC');
            $eventDatetime = new DateTime($datetime, $drupalDateTime);
            $utcDateTime = new DateTime($datetime, $utcTimezone);

            $offset = $drupalDateTime->getOffset($utcDateTime);
            $myInterval = DateInterval::createFromDateString((string)$offset . 'seconds');
            $eventDatetime->add($myInterval);

        } catch (\Exception $e) {
            throwException($e);
        }

        return $eventDatetime;
    }



}
