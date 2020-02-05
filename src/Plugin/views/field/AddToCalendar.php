<?php

namespace Drupal\add_to_calendar\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Component\Serialization\Json;

use Drupal\add_to_calendar\Entity\LinkCal;
use Drupal\add_to_calendar\Entity\Event;
use Drupal\add_to_calendar\Controller\CalendarController;
use Drupal;
/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("add_to_calendar")
 */
class AddToCalendar extends FieldPluginBase
{

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy()
  {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query()
  {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions()
  {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state)
  {
    parent::buildOptionsForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values)
  {
    //renders to the view field for add to calendar
    $node = $values->_entity;
    $nid =$node->id();
    $link = getCalendarEventLinks($nid);
    $calendarEvents = getCalendarEventFields($nid, $link);
    /*
     * return calendar event links to page
     */
    return [
      '#theme' => 'view_add_to_calendar',
      '#calendar_events' => $calendarEvents,
      '#nid' => $node->id(),
      '#attached' => array(
        'library' => array(
          'add_to_calendar/global_css',
          'add_to_calendar/global_js',
        ),
      )
    ];
  }
}

