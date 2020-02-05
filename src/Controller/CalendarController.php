<?php
/**
 * Created by PhpStorm.
 * User: grg3
 * Date: 8/20/18
 * Time: 1:32 PM
 */

namespace Drupal\add_to_calendar\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilder;

use Drupal\node\NodeInterface;
use Drupal\add_to_calendar\Entity\LinkCal;
use Drupal\add_to_calendar\Entity\Event;
use Drupal\add_to_calendar\Form\EventForm;
use Drupal\node\Entity\Node;
use Drupal;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Component\Serialization\Json;

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

    public function downloadCalendarEvent(NodeInterface $node){

        //$link = $this->getCalendarEventLinks($node->id());
      $link = getCalendarEventLinks($node->id());
        $filename = "calendar-event.ics";
        $icsFile = $link->icsFile();
        $response = new Response($icsFile);
        // Create the disposition of the file
        $disposition = $response->headers->makeDisposition(
          ResponseHeaderBag::DISPOSITION_ATTACHMENT,
          $filename
        );
        // Set the content disposition
        $response->headers->set('Content-Disposition', $disposition);
        //return download to page
        return $response;
    }

    public function sendEventEmail(NodeInterface $node) {

      /*
       * Builds modal form to send email along with calendar event ics file
       */
     // $link = $this->getCalendarEventLinks($node->id());
      $link = getCalendarEventLinks($node->id());

      $response = new AjaxResponse();
      if($link)
      {
        $send_form = new EventForm();
        $send_form->setFormId($node->id());
        // Get the modal form using the form builder.  form              options
        $modal_form = \Drupal::formBuilder()->getForm($send_form, array('ics'=>$link->icsFile(), 'nid'=>$node->id()));
        $options = [
          'dialogClass' => 'popup-dialog-class',
          'width' => '50%',
        ];
        // Add an AJAX command to open a modal dialog with the form as the content.
        $response->addCommand(new OpenModalDialogCommand('Add to Calendar', $modal_form, $options));
      }else{
        $modal['#prefix'] = '<div id="modal-form-success">';
        $modal['#suffix'] = '</div>';
        // The status messages that will contain any form errors.
        $modal['status_messages'] = [
          '#type' => 'status_messages',
          '#weight' => -10,
        ];
        drupal_set_message('There was a problem sending your message and it was not sent.', 'error');
        $response->addCommand(new OpenModalDialogCommand($this->t('Error'), $modal, ['width' => 800]));
      }
      //return response to page
      return $response;
    }
}

