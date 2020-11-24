<?php

namespace Drupal\custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class GoogleSearchController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    $keyword = \Drupal::request()->get('q');

    return [
      '#type' => 'inline_template',
      '#template' => "<div class=\"search-bar\">
                        <div class=\"grid-container large\">
                          <form action='/search-results'>
                            <div class=\"input-group\">
                              <input class=\"input-group-field\" type=\"search\" placeholder=\"Enter search keywords...\" value='{{ value }}'>
                               <div class=\"input-group-button\">
                            <a class=\"button secondary\" href=\"javascript:void(0)\"><i class=\"fas fa-search\"></i> <span class=\"show-for-large\">Search</span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class='gcse-searchresults-only' autoSearchOnLoad='true'></div>",
      '#context' => [
        'heading' => 'Search UCSC',
        'screen_reader' => 'Search',
        'value' => is_string($keyword) ? $keyword : '',
      ],
    ];
  }

}
