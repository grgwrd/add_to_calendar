<?php

namespace Drupal\add_to_calendar\Entity;

use DateTime;

use Drupal\add_to_calendar\Generators\Ics;
use Drupal\add_to_calendar\Generators\IcsFile;
use Drupal\add_to_calendar\Generators\Yahoo;
use Drupal\add_to_calendar\Generators\Google;
use Drupal\add_to_calendar\Exceptions\InvalidLink;

/**
 * @property string $title
 * @property \DateTime $from
 * @property \DateTime $to
 * @property string $description
 * @property string $address
 */
class LinkCal
{
    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $from;

    /** @var \DateTime */
    protected $to;

    /** @var string */
    protected $description;

    /** @var string */
    protected $address;

    public function __construct($title, DateTime $from, DateTime $to)
    {
        $this->title = $title;

        if ($to < $from) {
            throw InvalidLink::invalidDateRange($from, $to);
        }

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @param string $title
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return static
     */
    public static function create($title, DateTime $from, DateTime $to)
    {
        return new static($title, $from, $to);
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function address($address)
    {
        $this->address = $address;

        return $this;
    }

    public function google()
    {
        return (new Google())->generate($this);
    }

    public function ics()
    {
        return (new Ics())->generate($this);
    }

    public function icsFile()
    {
        return (new IcsFile())->generate($this);
    }

    public function yahoo()
    {
        return (new Yahoo())->generate($this);
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
