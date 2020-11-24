<?php

namespace Drupal\custom_module\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Render\Markup;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;

/**
 * Plugin implementation of the 'UcscButtonsFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ucsc_buttons_link",
 *   label = @Translation("Ucsc Buttons Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class UcscButtonsFormatter extends LinkFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = parent::viewElements($items, $langcode);

    foreach ($elements as $delta => &$element) {
      $element['#title'] = Markup::create($element['#title']);
      $element['#options']['attributes']['class'] = $this->buttonClass(!$delta);
    }

    return $elements;
  }

  /**
   * Primary buttons.
   *
   * @param false $primary
   *
   * @return string[]
   */
  private function buttonClass($primary = FALSE) {
    if ($primary) {
      return ['button', 'primary'];
    }
    else {
      return ['button', 'secondary'];
    }
  }

}
