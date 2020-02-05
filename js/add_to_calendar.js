(function (jQuery, Drupal, drupalSettings) {
    Drupal.behaviors.add_to_calendar = {
        attach: function attach(context) {

            $ = jQuery;

            function ButtonLinks(){
                this.buttonId = 0;
                this.openToggle = "";
            }

            var buttonLink = new ButtonLinks();
            var buttonClickCount = 0;

            $('body', context).click(function(event){

                buttonClickCount++;

                if(buttonClickCount === 1 && $(buttonLink.openToggle).hasClass('add-cal-open')){
                    $('div.add-cal-open').stop().slideToggle("fast", function () {
                        $(this).removeClass('add-cal-open');
                    });

                    buttonLink.openToggle = "";
                    buttonLink.buttonId = 0;
                }

                buttonClickCount = 0;
            });

            $('button.add-to-cal', context).click(function () {

                buttonClickCount++;
                var prevButtonLink = buttonLink.openToggle;

                buttonLink.buttonId = this.id;
                buttonLink.openToggle = 'div.dropdown-content-'+buttonLink.buttonId;

                //close left open add to calendar menu button
                if(buttonLink.openToggle !== prevButtonLink && $(prevButtonLink).hasClass('add-cal-open')) {
                    $(prevButtonLink).stop().slideToggle("fast");
                    $(prevButtonLink).removeClass('add-cal-open');
                }


                //close add to calendar menu button
                if($(buttonLink.openToggle).hasClass('add-cal-open')) {
                    $(buttonLink.openToggle).stop().slideToggle("fast");
                    $(buttonLink.openToggle).removeClass('add-cal-open');
                    buttonLink.openToggle = "";
                //open add to calendar menu button
                }else {
                    $(buttonLink.openToggle).stop().slideToggle("fast");
                    $(buttonLink.openToggle).addClass('add-cal-open');
                }


            });

            //close add to calendar menu button when link is clicked
            $('.close-cal-menu', context).click(function () {
                if ($(buttonLink.openToggle).hasClass('add-cal-open')) {
                    $('div.add-cal-open').stop().slideToggle("fast", function () {
                        $(this).removeClass('add-cal-open');
                    });

                    buttonLink.openToggle = "";
                    buttonLink.buttonId = 0;
                }

            });

        }
    };
})(jQuery, Drupal, drupalSettings);
