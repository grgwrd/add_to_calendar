<?php

namespace Drupal\add_to_calendar_field\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Component\Serialization\Json;

use Drupal\add_to_calendar_field\Entity\LinkCal;
use Drupal\add_to_calendar_field\Entity\Event;

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

        $addEvent = new Event($node->id());

        $location = $addEvent->getLocation();
        $description = $addEvent->getDescription();
        $title = $addEvent->getTitle();

        $to = $addEvent->getTo();
        $from = $addEvent->getFrom();

        $link = LinkCal::create($title, $from, $to)
            ->description($description)
            ->address($location);

        $link_url = Url::fromRoute('send_mail.SendEventEmail', array('node'=>$node->id()));

        $link_url->setOptions([
            'attributes' => [
                'class' => ['use-ajax'],
                'data-dialog-type' => 'modal',
                'data-dialog-options' => Json::encode(['width' => 400]),
            ]
        ]);

        $send_email = array(
            '#type' => 'markup',
            '#markup' => Link::fromTextAndUrl(t('Send Email'), $link_url)->toString(),
            '#attached' => ['library' => ['core/drupal.dialog.ajax']]
        );

        $googleLink = array(
            '#type' => 'link',
            '#title' => 'Google',
            '#attributes' => [
                'class' => 'close-cal-menu',
                'target' => '_blank',
            ],
            '#url' => Url::fromUri($link->google()),
        );

        $yahooLink = array(
            '#type' => 'link',
            '#title' => 'Yahoo',
            '#attributes' => [
                'class' => 'close-cal-menu',
                'target' => '_blank',
            ],
            '#url' => Url::fromUri($link->yahoo()),
        );

        return [
            '#theme' => 'add_to_calendar_field',
            '#googleAddCal' => $googleLink,
            '#yahooAddCal' =>  $yahooLink,
            '#icsAddCal' => $link->ics(),
            '#nid'=>$node->id(),
            '#send_email_link'=>$send_email,
            '#attached' => array(
                'library' => array(
                    'add_to_calendar_field/global_js',
                ),
            )
        ];

    }

}
