/**
 * @file
 * Placeholder file for custom sub-theme behaviors.
 *
 */
(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Use for short things.
   *
   * @type {{attach: Drupal.behaviors.common.attach}}
   */
  Drupal.behaviors.common = {
    attach: function (context, settings) {

      // A-Z listing Search
      // Setup before functions.
      var typingTimer;
      var doneTypingInterval = 500;
      var $input = $('.search-bar .input-group-field');
      var $search_input = $('input[name="link"]');
      if ($input.length) {

        /**
         * Finish Typing.
         */
        function finishTyping() {
          $search_input.val($input.val());
          $search_input.trigger('change');
        }

        $input.on('keyup', function () {
          clearTimeout(typingTimer);
          typingTimer = setTimeout(finishTyping, doneTypingInterval);
        });
        $input.on('keydown', function () {
          clearTimeout(typingTimer);
        });
      }
    }
  };

  /**
   * Featured Slideshow.
   *
   * @type {{attach: Drupal.behaviors.featureSlideshow.attach}}
   */
  Drupal.behaviors.featureSlideshow = {
    attach: function (context, settings) {
      var $context = $(context);

      $context.find('.featured-slideshow.owl-carousel').once('featureSlideshow').each(function () {
        $(this).owlCarousel({
          autoHeight: true,
          nav: true,
          dots: false,
          responsive: {
            0: {
              items: 1
            },
            900: {
              items: 3
            }
          }
        });
      });
    }
  };

  /**
   * Profile Owl.
   *
   * @type {{attach: Drupal.behaviors.profileOwl.attach}}
   */
  Drupal.behaviors.profileOwl = {
    attach: function (context, settings) {
      var $context = $(context);
      // .profile-list, .fact-timeline, .job-posting-card-list
      $context.find('.profile-list.owl-carousel').once('profileOwl').each(function () {
        $(this).owlCarousel({
          margin: 30,
          nav: true,
          dots: false,
          items: 3,
          loop: true,
          responsive: {
            0: {
              items: 1
            },
            640: {
              items: 2
            },
            1024: {
              items: 3,
              autoWidth: false
            }
          }
        });
      });
    }
  };

  /**
   * Gallery Owl.
   *
   * @type {{attach: Drupal.behaviors.galleryOwl.attach}}
   */
  Drupal.behaviors.galleryOwl = {
    attach: function (context, settings) {
      var $context = $(context);
      // .profile-list, .fact-timeline, .job-posting-card-list
      $context.find('.image-gallery-carousel.owl-carousel').once('galleryOwlf').each(function () {
        $(this).owlCarousel({
          margin: 6,
          autoHeight: true,
          dots: false,
          nav: true,
          responsive: {
            0: {
              items: 1
            },
            640: {
              items: 2
            },
            1024: {
              autoWidth: true,
              autoHeight: false
            }
          }
        });
      });
      $context.find('#lightgallery').once('lightgal').each(function () {
        $(this).lightGallery({
          selector: '.lightgallery-item',
          showThumbByDefault: false,
          thumbnail: false
          //https://sachinchoolur.github.io/lightGallery/docs/api.html#lightgallery-core
        });
      });
    }
  };

  /**
   * "Job Posting Card" Carousel.
   *
   * @type {{attach: Drupal.behaviors.jobPostingCardOwl.attach}}
   */
  Drupal.behaviors.jobPostingCardOwl = {
    attach: function (context, settings) {
      var $context = $(context);

      $context.find('.job-posting-card-list.job-card.owl-carousel').once('jobPostingCardOwl').each(function () {
        $(this).owlCarousel({
          margin: 30,
          nav: true,
          dots: false,
          items: 3,
          loop: true,
          responsive: {
            0: {
              items: 1
            },
            640: {
              items: 2
            },
            1024: {
              items: 3,
              autoWidth: false
            }
          }
        });
      });

      $context.find('.component-wrapper .job-posting-card-list.list.owl-carousel').once('jobPostingCardMinimalOwl').each(function () {
        $(this).owlCarousel({
          margin: 30,
          nav: true,
          dots: false,
          items: 3,
          loop: true,
          responsive: {
            0: {
              items: 1
            },
            640: {
              items: 2
            },
            1024: {
              items: 3,
              autoWidth: false
            }
          }
        });
      });
    }
  };

  /**
   * "Historical Timeline" Carousel.
   *
   * @type {{attach: Drupal.behaviors.historicalTimelineOwl.attach}}
   */
  Drupal.behaviors.historicalTimelineOwl = {
    attach: function (context, settings) {
      var $context = $(context);
      $context.find('.fact-timeline.owl-carousel').once('historicalTimelineOwl').each(function () {
        $(this).owlCarousel({
          margin: 30,
          nav: true,
          dots: false,
          items: 3,
          loop: true,
          responsive: {
            0: {
              items: 1
            },
            640: {
              items: 2
            },
            1024: {
              items: 3,
              autoWidth: false
            }
          }
        });
      });
    }
  };

  /**
   * A common tweaks for global navigation.
   *
   * @type {{attach: Drupal.behaviors.globalNavigation.attach}}
   */
  Drupal.behaviors.globalNavigation = {
    attach: function (context, settings) {
      var $context = $(context),
        $globalSearchToggler = $context.find('.global-search-toggle');

      $context.find('.global-search-toggle').attr('data-toggle', 'global-search');

      $globalSearchToggler.once('globalSearchToggler').each(function () {

        $(this).on('click', function (event) {
          event.preventDefault();
        });

        $(this).attr({
          'data-toggle': 'global-search',
          'aria-expanded': 'false',
          'aria-controls': 'global-search'
        });
      })

    }
  };

  /**
   * Remove duplicates of output for Desktop and Mobile versions.
   *
   * @type {{attach: Drupal.behaviors.sidebarNavigation.attach}}
   */
  Drupal.behaviors.sidebarNavigation = {
    attach: function (context, settings) {
      $(context).find('.sidebar-navigation-mobile .sidebar-navigation-desktop').remove();
      $(context).find('.sidebar-first .sidebar-navigation-mobile').remove();
      $(context).find('.page-menu li.title a').addClass('section-title');
    }
  };

  /**
   * Subsite Navigation.
   *
   * @type {{attach: Drupal.behaviors.subSiteNavigation.attach}}
   */
  Drupal.behaviors.subSiteNavigation = {
    attach: function (context, settings) {
      var $context = $(context),
        $subMenuToggleLinks = $context.find('a.sub-menu-toggle');

      $subMenuToggleLinks.on('click', function (event) {
        event.preventDefault();
      });

      $subMenuToggleLinks.once('addToggleAttributes').each(function () {
        $(this).attr({
          'data-toggle': 'filter-form',
          'aria-controls': 'filter-form'
        }).wrapInner('<strong></strong>');
      });
    }
  };

  /**
   * Ready Function.
   */
  $(document).ready(function () {
    /** Archive Form.
     *
     * @type {*|jQuery|HTMLElement}
     */
    var $archiveForm = $('#views-exposed-form-teaser-archive-news-archive-page'),
      labelValue = $archiveForm.find('.form-item-field-categories-target-id label').text();

    $archiveForm.find("a.filter-category").prepend(labelValue);
    $('.view-teaser-archive').find('.news-card-list .news-card:first-child, .news-card-list .news-card:nth-child(2)').wrapAll("<div class='news-card-list split-column'></div>");

    /*
    Parameter
     */
    var getUrlParameter = function getUrlParameter(sParam) {
      var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

      for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
      }
    };
    var year = getUrlParameter('year');
    if (year) {
      $archiveForm.find('a.filter-year').prepend(year);
    }
    else {
      $archiveForm.find('a.filter-year').prepend(new Date().getFullYear());
    }
  });

})(jQuery, Drupal, drupalSettings);
