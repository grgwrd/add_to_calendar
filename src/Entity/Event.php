<?php
/**
 * Created by PhpStorm.
 * User: grg3
 * Date: 9/19/18
 * Time: 3:37 PM
 */

namespace Drupal\add_to_calendar_field\Entity;
use \Datetime;
use \Datetimezone;

use \Drupal\node\Entity\Node;

class Event
{
    //fields from view replacement pattern
    protected $title;

    protected $startDate;

    protected $endDate;

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

    public function __construct($nid)
    {

        $node = Node::load($nid);

        $startDate = $node->get('field_event_start_date_time')->getValue();
        $this->setStartDate($startDate);

        $endDate = $node->get('field_event_end_date_time')->getValue();
        $this->setEndDate($endDate);

        $location = $node->get('field_event_specific_location')->getValue();
        $this->setLocation($location[0]['value']);

        $title = $node->get('title')->getValue();
        $this->setTitle($title[0]['value']);

        $description = $node->get('field_event_description')->getValue();
        $this->setDescription($description[0]['value']);

    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }


    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = str_replace('T', ' ', $startDate[0]['value']);

        $this->setFrom($this->startDate);
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate =  str_replace('T', ' ', $endDate[0]['value']);;

        $this->setTo($this->endDate);
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
     * @param mixed $endDate
     */
    public function setTo($endDate)
    {
        $timezone = new DateTimeZone('America/Chicago');

        $to = new Datetime($endDate, $timezone);

        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $startDate
     */
    public function setFrom($startDate)
    {
        $timezone = new DateTimeZone('America/Chicago');

        $from = new Datetime($startDate, $timezone);

        $this->from = $from;
    }

}