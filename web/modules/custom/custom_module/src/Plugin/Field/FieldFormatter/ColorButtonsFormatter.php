<?php

namespace Drupal\custom_module\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Render\Markup;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;

/**
 * Plugin implementation of the 'ColorButtonsFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "color_buttons_link",
 *   label = @Translation("Color Buttons Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class ColorButtonsFormatter extends LinkFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = parent::viewElements($items, $langcode);

    $entity = $items->getEntity();

    foreach ($elements as &$element) {
      $element['#title'] = Markup::create($element['#title']);

      if ($entity instanceof ContentEntityInterface &&
        $entity->hasField('field_button_list_color') &&
        !$entity->get('field_button_list_color')->isEmpty()) {
        // Get color.
        $color = $entity->get('field_button_list_color')->value;
        $element['#options']['attributes']['class'] = ['button', $color];
      }
    }

    return $elements;
  }

}
