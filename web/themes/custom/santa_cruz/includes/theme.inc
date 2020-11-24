<?php

/**
 * @file
 * Custom theme hooks.
 */

/**
 * Implements template_preprocess_region.
 *
 * @param $variables
 */
function santa_cruz_preprocess_region(&$variables) {
  $region = @$variables['region'];

  switch ($region) {
    case 'footer_first':
    case 'footer_middle':
      $variables['attributes']['class'] = [
        'grid-x',
        'grid-margin-x',
        'grid-padding-x',
        'large-padding-collapse',
        'large-margin-collapse',
      ];
      break;

    case 'footer_last':
      $variables['attributes']['class'] = [
        'grid-x',
        'grid-margin-x',
        'text-center',
        'mt-25',
      ];
      break;
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_page_title(&$variables) {
  $variables['title_attributes']['class'] = ['underline'];
}
