/**
 * @file
 * Attaches the behaviors for the shuffle module.
 */


(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.portfolio = {
    attach: function (context) {
	$(window).on('load', function () {	
      $.each(drupalSettings.portfolio, function (id) {
        var sizerMethod = this.sizerMethod;

        /* The selectors we have to play with */
        var $grid = $('.' + id + ' .shuffle-container');
        var $filterOptions = $('.' + id + ' .filter-options');

         // None of these need to be executed synchronously.
        setTimeout(function () {
          setupFilters();
        }, 100);

        //$grid.find('.shuffle-item');
		$grid.shuffle({
            itemSelector: '.shuffle-item',
            speed: this.speed,
            easing: this.easing,
            sizer: null,
        });
		
        function listen() {
          var debouncedLayout = _.throttle(function () {
            $grid.shuffle('update');
          }, 300);

          // Get all images inside shuffle.
          $grid.find('img').each(function () {
            var proxyImage;

            // Image already loaded.
            if (this.complete && this.naturalWidth) {
              return;
            }

            // If none of the checks above matched, simulate loading on detached element.
            proxyImage = new Image();
            $(proxyImage).on('load', function () {
              $(this).off('load');
              debouncedLayout();
            });

            proxyImage.src = this.src;
          });

          // Because this method doesn't seem to be perfect.
          setTimeout(function () {
            debouncedLayout();
          }, 500);
        }
		
        function setupFilters() {
          var $btns = $filterOptions.children();
          $btns.unbind('click').on('click', function () {
            var $this = $(this);
            var isActive = $this.hasClass('active');
            var group = isActive ? 'all' : $this.data('group');

            // Hide current label, show current label in title.
            if (!isActive) {
              $('.filter-options .active').removeClass('active');
            }

            $this.toggleClass('active');

            // Filter elements.
            $grid.shuffle('shuffle', group);
          });

          $btns = null;
        }

      });
	
	});
	
    }
  };
})(jQuery, Drupal, drupalSettings);