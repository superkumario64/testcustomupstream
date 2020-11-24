<?php

/**
 * @file
 * Theme and preprocess functions for paragraphs.
 */

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__tabs_horizontal(&$variables) {
  $paragraph = $variables['paragraph'];
  if ($paragraph instanceof Paragraph) {
    $acc = $paragraph->get('field_paragraphs');
    $count = $acc->count();
    $tab_hor = [];
    for ($i = 0; $i < $count; $i++) {
      $para_tab = $variables['content']['field_paragraphs'][$i]['#paragraph'];
      $tab_hor[$i]['title'] = $para_tab->get('field_title')
        ->first()
        ->getValue()['value'];
      $tab_hor[$i]['body'] = [
        '#type' => 'processed_text',
        '#text' => $para_tab->get('field_body_formatted')->value,
        '#format' => $para_tab->get('field_body_formatted')->format,
      ];
    }

    $variables['field__tab_hori_array'] = $tab_hor;
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__content_with_media(&$variables) {
  $paragraph = $variables['paragraph'];
  if ($paragraph instanceof Paragraph) {
    $variables['field__media_position_class'] = '';
    if ($paragraph->hasField('field_show_background_style')
      && !$paragraph->get('field_show_background_style')
        ->isEmpty() && $paragraph->get('field_show_background_style')
        ->first()
        ->getValue()['value']) {
      $variables['field__media_position_class'] = 'wave-pattern blue';
    }
    if ($paragraph->hasField('field_media_position')
      && !$paragraph->get('field_media_position')
        ->isEmpty()) {
      if ($paragraph->get('field_media_position')
          ->first()
          ->getValue()['value'] == "left") {
        $variables['field__media_position_class'] .= ' right';
      }
      else {
        $variables['field__media_position_class'] .= ' left';
      }
    }
  }

}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__grid_content_left_item(&$variables) {
  $paragraph = $variables['paragraph'];
  if ($paragraph instanceof Paragraph) {
    if ($paragraph->hasField('field_cta_style')
      && !$paragraph->get('field_cta_style')->isEmpty()) {
      $variables['field_cta_style__class'] = 'button primary';
      if ($paragraph->get('field_cta_style')
          ->first()
          ->getValue()['value'] == "btn-link") {
        $variables['field_cta_style__class'] = 'btn-link';
      }
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__step(&$variables) {
  $variables['delta'] = NULL;
  foreach ($variables['paragraph']->getParentEntity()
             ->get('field_paragraphs')
             ->getValue() as $delta => $item) {
    if ($item['target_id'] === $variables['paragraph']->id()) {
      $variables['delta'] = $delta + 1;
      break;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__gallery(&$variables) {
  $paragraph = $variables['paragraph'];
  if ($paragraph instanceof Paragraph) {
    if ($paragraph->hasField('field_node_ref')
      && !$paragraph->get('field_node_ref')->isEmpty()) {
      $img_ref_obj = $paragraph->referencedEntities()[1]->referencedEntities();
      $img_ref_url_arr = [];
      $group_cou = 1;
      $image_cou = 1;
      $mode_cou = 1;
      foreach ($img_ref_obj as $media_obj) {
        if ($media_obj->getEntityTypeId() == "media") {
          // It is for image media.
          $uri = $media_obj->get('field_media_image')->entity->getFileUri();
          if ($group_cou % 3 == 1) {
            // Here you will get URL of uploaded image.
            $img_ref_url_arr[$group_cou]['image'][$image_cou] = file_create_url($uri);
            if ($mode_cou == 1) {
              $img_ref_url_arr[$group_cou]['class'] = 'featured';
              $mode_cou = 2;
            }
            else {
              $img_ref_url_arr[$group_cou]['class'] = 'portrait';
              $mode_cou = 1;
            }
            $group_cou++;
          }
          else {
            // Here you will get URL of uploaded image.
            $img_ref_url_arr[$group_cou]['image'][$image_cou] = file_create_url($uri);
            $img_ref_url_arr[$group_cou]['class'] = 'landscape';
            if ($image_cou == 2) {
              $image_cou = 1;
              $group_cou++;
            }
            else {
              $image_cou++;
            }
          }
        }
      }
      $variables['img_ref_url_arr'] = $img_ref_url_arr;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function santa_cruz_preprocess_paragraph__factoid_card(&$variables) {
  $variables['delta'] = NULL;
  $neighbors = $variables['paragraph']->getParentEntity()
    ->get('field_paragraphs')
    ->getValue();
  foreach ($neighbors as $delta => $item) {
    if ($item['target_id'] === $variables['paragraph']->id()) {
      $variables['color'] = 'green';

      switch ($delta) {
        case 1:
          $variables['color'] = 'pink';

          break;

        case 2:
          $variables['color'] = 'blue';

          break;

        case 3:
          $variables['color'] = 'secondary-blue';

          break;
      }

      break;
    }
  }
}
