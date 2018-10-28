<?php
namespace Drupal\add_to_calendar_field\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Form\FormBuilder;

use Drupal\node\NodeInterface;

use Drupal\add_to_calendar_field\Entity\LinkCal;
use Drupal\add_to_calendar_field\Entity\Event;
use Drupal\add_to_calendar_field\Form\EventForm;

class CalendarController extends ControllerBase {

    /**
     * The form builder.
     *
     * @var \Drupal\Core\Form\FormBuilder
     */
    protected $formBuilder;

    /**
     * The ModalFormExampleController constructor.
     *
     * @param \Drupal\Core\Form\FormBuilder $formBuilder
     *   The form builder.
     */
    public function __construct(FormBuilder $formBuilder) {
        $this->formBuilder = $formBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The Drupal service container.
     *
     * @return static
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('form_builder')
        );
    }

    public function sendEventEmail(NodeInterface $node) {

        $addEvent = new Event($node->id());

        $location = $addEvent->getLocation();
        $description = $addEvent->getDescription();
        $title = $addEvent->getTitle();

        $to = $addEvent->getTo();
        $from = $addEvent->getFrom();


        $link = LinkCal::create($title, $from, $to)
            ->description($description)
            ->address($location);

        $form_options = array('ics'=>$link->icsFile(), 'nid'=>$node->id());

        $send_form = new EventForm();

        $send_form->setFormId($node->id());

        // Get the modal form using the form builder.
        $modal_form = \Drupal::formBuilder()->getForm($send_form, $form_options);

        $options = [
            'dialogClass' => 'popup-dialog-class',
            'width' => '50%',
        ];

        $response = new AjaxResponse();

        // Add an AJAX command to open a modal dialog with the form as the content.
        $response->addCommand(new OpenModalDialogCommand('Add to Calendar', $modal_form, $options));

        return $response;
    }
}
