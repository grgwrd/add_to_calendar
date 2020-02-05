# Add To Calendar

#### Drupal 8 module to generate calendar links for adding events to calendar with Send Email, Google, Yahoo, and ICS download.

## Getting Started:
### 1. Create content type for calendar event.
##### REQUIRED FIELDS FOR CONTENT TYPE:
* Title for event (text)
* Start Date time (datetime)
* End Date time (datetime)

##### OPTIONAL FIELDS FOR CONTENT TYPE:
* Location for event (text)
* Description for event (text)

### 2. Configure add to calendar module
* Go to admin configuration and find Add To Calendar Configuration
* Configure Add to Calendar settings for machine name fields for your content type.
* Clear Drupal cache to load new configuration settings.

### 3. Optional configuration under Calendar event links settings
* You can disable and re-enable calendar events links to display for your content type.

# Add To Calendar for Drupal 8 Views

#### You can add a field for the calendar links inside of a view. You must follow these directions otherwise the view will not work.
* Create a view that uses your content type.
* If you are using fields you can find a field for Add to Calendar Field.
* Look under the Global category and select Add To Calendar Field.
* Then it should display as part of your view.

<h4 style="color:red;">CAUTION: Adding the Add to Calendar field to your view will destroy your view if you ever decide to uninstall the Add To Calendar Field module. Be sure to delete the Add To Calendar field from your views before uninstalling the module. </h4>
