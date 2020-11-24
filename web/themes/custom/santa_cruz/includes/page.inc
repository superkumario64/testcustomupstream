<?php

/**
 * @file
 * Theme and preprocess functions for pages.
 */

use Drupal\Core\Site\Settings;

/**
 * Implements template_preprocess_page().
 */
function santa_cruz_preprocess_page(&$variables) {
  // Force a sub site header/footer elements for debugging by adding /?force-subsite to a request.
  // Or always add $settings['is_subsite'] = TRUE; for each sub site.
  if (Settings::get('is_subsite', FALSE) === TRUE) {
    $variables['page']['is_subsite'] = TRUE;
  }
}
