<?php

namespace Drupal\custom_module\Plugin\Block;

use Drupal\block_content\BlockContentInterface;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\Core\Cache\Cache;

/**
 * Provides the block for displaying Site Title in the header.
 *
 * @Block(
 *   id = "custom_module_subsite_logo_desktop",
 *   admin_label = @Translation("Subsite Logo or Title - For Desktop"),
 *
 *   category = @Translation("SITE Custom Block"),
 *   forms = {
 *     "settings_tray" = FALSE,
 *   },
 * )
 */
class SubSiteLogoTitleDesktopBlock extends BlockBase {

  use StringTranslationTrait;

  /**
   * Subsite Logo or Title block ID.
   */
  const SUBSITE_LOGO_BLOCK_ID = 16;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account, $return_as_object = FALSE) {
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block = BlockContent::load(self::SUBSITE_LOGO_BLOCK_ID);

    $linkTitle = '';
    $linkUrl = '';
    $logoUrl = '';
    $logoAlt = '';
    $logoTitle = '';
    $subSiteTitle = '';

    if ($block instanceof BlockContentInterface) {
      if ($block->hasField('field_title')
        && $block->get('field_title')->isEmpty()
        && $block->hasField('field_link')
        && !$block->get('field_link')->isEmpty()
        && $block->hasField('field_media')
        && $block->get('field_media')->isEmpty()) {
        $link = $block->get('field_link')->first()->getValue();
        $linkTitle = $link['title'];
        $linkUrl = Url::fromUri($link['uri'])->toString();

        $markup = '<a href="' . $linkUrl . '" aria-label="' . $linkTitle . '"><h1>' . $linkTitle . '</h1></a>';

        return [
          '#type' => 'markup',
          '#title' => $linkTitle,
          '#markup' => $markup,
        ];
      }

      if ($block->hasField('field_title')
        && !$block->get('field_title')->isEmpty()
        && $block->hasField('field_link')
        && !$block->get('field_link')->isEmpty()
        && $block->hasField('field_media')
        && $block->get('field_media')->isEmpty()) {
        $link = $block->get('field_link')->first()->getValue();
        $linkTitle = $link['title'];
        $linkUrl = Url::fromUri($link['uri'])->toString();
        $subSiteTitle = $block->get('field_title')->getString();

        $markup = '<h1>' . $subSiteTitle . ' <span class="hide-for-xlarge"> | '
          . $linkTitle . '</span></h1><a class="div show-for-xlarge" href="' . $linkUrl . '">' . $linkTitle . '</a>';

        return [
          '#type' => 'markup',
          '#title' => $linkTitle,
          '#markup' => $markup,
        ];
      }

      if ($block->hasField('field_link')
        && !$block->get('field_link')->isEmpty()) {
        $link = $block->get('field_link')->first()->getValue();
        $linkTitle = $link['title'];
        $linkUrl = Url::fromUri($link['uri'])->toString();
      }
      if ($block->hasField('field_media')
        && !$block->get('field_media')->isEmpty()) {
        $media = $block->get('field_media')
          ->first()
          ->get('entity')
          ->getTarget()
          ->getValue()
          ->get('field_media_image')
          ->first();

        $file = $media->get('entity')
          ->getTarget()
          ->getValue()
          ->getFileUri();

        $logoUrl = Url::fromUri(file_create_url($file))->toString();
        $logoAlt = $media->alt;
        $logoTitle = $media->title;
      }
    }

    $markup = '<h1><div class="subsite-brand-container">';

    if ($logoUrl !== '') {
      $markup .= '<img class="subsite-brand" src="' . $logoUrl . '" title="' . $logoTitle . '" alt="' . $logoAlt . '">';
    }

    $markup .= '</div></h1>';

    if ($linkTitle !== '') {
      $markup .= '<a class="div" href="' . $linkUrl . '">' . $linkTitle . '</a>';
    }

    return [
      '#type' => 'markup',
      '#title' => $linkTitle,
      '#markup' => $markup,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // With this when your node change your block will rebuild.
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      // If there is node add its cachetag.
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $node->id()]);
    }
    else {
      // Return default tags instead.
      return parent::getCacheTags();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // If you depends on \Drupal::routeMatch()
    // you must set context of this block with 'route' context tag.
    // Every new route this block will rebuild.
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

}
