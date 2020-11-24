<?php

namespace Drupal\custom_module\Plugin\Block;

use Drupal\block_content\BlockContentInterface;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\Cache;

/**
 * Provides the block for displaying Site Title in the header.
 *
 * @Block(
 *   id = "custom_module_subsite_logo_mobile",
 *   admin_label = @Translation("Subsite Logo or Title - For Mobile"),
 *   category = @Translation("SITE Custom Block"),
 *   forms = {
 *     "settings_tray" = FALSE,
 *   },
 * )
 */
class SubSiteLogoTitleMobileBlock extends BlockBase {

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
    $subSiteTitle = '';

    if ($block instanceof BlockContentInterface) {
      if ($block->hasField('field_link')
        && !$block->get('field_link')->isEmpty()) {
        $link = $block->get('field_link')->first()->getValue();
        $linkTitle = $link['title'];
      }

      $request = \Drupal::request();
      $route_match = \Drupal::routeMatch();
      $subSiteTitle = \Drupal::service('title_resolver')
        ->getTitle($request, $route_match->getRouteObject());
    }

    $markup = '';

    if ($subSiteTitle !== '' && $linkTitle !== '') {
      $markup .= '<p>';
    }

    if ($subSiteTitle !== '') {
      $markup .= $subSiteTitle;
    }

    if ($subSiteTitle !== '' && $linkTitle !== '') {
      $markup .= ' - ';
    }

    if ($linkTitle !== '') {
      $markup .= $linkTitle;
    }

    if ($subSiteTitle !== '' && $linkTitle !== '') {
      $markup .= '</p>';
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
