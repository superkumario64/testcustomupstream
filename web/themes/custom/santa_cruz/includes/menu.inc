<?php

/**
 * @file
 * Theme and preprocess functions for menus.
 */

/**
 * Implements hook_preprocess_menu().
 */
function santa_cruz_preprocess_menu(array &$variables) {
  $name = @$variables['menu_name'];

  switch ($name) {
    case 'footer':
      $variables['attributes']['class'][] = 'horizontal';
      break;

    case 'footer---connect-to-to-ucsc':
      $variables['attributes'] = [];
      break;

    case 'footer---engage-with-ucsc':
      $variables['attributes']['class'][] = 'vertical';
      break;

    case 'footer---legal-links':
      $variables['attributes']['class'] = ['primary-set'];
      break;
  }
}
