<?php

/**
 * @file
 * Theme and preprocess functions for blocks.
 */

use Drupal\block\Entity\Block;

/**
 * Implements hook_preprocess_HOOK().
 */
//function santa_cruz_preprocess_block(&$variables)
//{
//
//}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function santa_cruz_theme_suggestions_block_alter(array &$suggestions, array $variables) {
  if (!empty($variables['elements']['#id'])) {
    $block = Block::load($variables['elements']['#id']);
    $region = $block->getRegion();
    $suggestions[] = 'block__' . $region;
    $suggestions[] = 'block__' . $region . '__' . $variables['elements']['#id'];
  }
  /* Use this 'else if' only if you are using page_manager module and want to know which region is the block. */
  else {
    if (isset($variables['elements']['#configuration']['region'])) {
      $exposed_plugin_id = explode(':', $variables['elements']['#plugin_id']);
      $suggestions[] = 'block__page_' . $variables['elements']['#configuration']['region'] . '__' . end($exposed_plugin_id);
    }
  }

  return $suggestions;
}
