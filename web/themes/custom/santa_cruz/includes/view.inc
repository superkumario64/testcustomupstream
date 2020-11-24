<?php

/**
 * @file
 * Theme and preprocess functions for views.
 */

/**
 * template_preprocess_views_view
 *
 * @param $variables
 */
function santa_cruz_preprocess_views_view(&$variables) {
  if ($variables['id'] === 'a_z_list' && $variables['display_id'] === 'page_1') {
    $exposed = &$variables['exposed'];
    $exposed['#attributes']['class'] = array_merge($exposed['#attributes']['class'], [
      'grid-x',
      'grid-margin-x',
    ]);

    // Hide search fields.
    $link = &$exposed['link'];
    if ($link) {
      $link['#prefix'] = '<div class="hide">';
      $link['#suffix'] = '</div>';
    }

    // Add classes to fields.
    $_fields = ['audiences', 'categories'];
    foreach ($_fields as $field_key) {
      $field = &$exposed[$field_key];
      if ($field) {
        $field['#prefix'] = '<div class="medium-6 cell">';
        $field['#suffix'] = '</div>';
      }
    }
  }
}
