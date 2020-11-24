<?php

/**
 * Implements hook_css_alter().
 */
//function santa_cruz_css_alter(&$css) {
//  // Use <link> as opposed to <style> @import during development if
//  // css preprocessing is turned off. This will allow tools like
//  // BrowserSync and LiveReload to work properly.
//  $preprocess_css = \Drupal::config('system.performance')->get('css.preprocess');
//  if (isset($preprocess_css) && $preprocess_css === FALSE) {
//    foreach ($css as $key => $value) {
//      $css[$key]['preprocess'] = FALSE;
//    }
//  }
//}
