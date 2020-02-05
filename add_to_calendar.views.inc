<?php

/**
 * @file
 * Contains add_to_calendar\add_to_calendar.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Render\Markup;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\system\ActionConfigEntityInterface;

/**
* Implements hook_views_data().
*/
function add_to_calendar_views_data() {

    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];


    $data['views']['add_to_calendar'] = [
        'title' => t('Add To Calendar'),
        'help' => t('Add this field to include links to add to use calendar event content type'),
        'field' => [
            'id' => 'add_to_calendar',
        ],
    ];

    return $data;
}
