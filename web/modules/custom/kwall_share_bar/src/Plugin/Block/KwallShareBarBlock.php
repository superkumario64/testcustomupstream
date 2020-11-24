<?php

namespace Drupal\kwall_share_bar\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'KwallShareBarBlock' block.
 *
 * @Block(
 *  id = "kwall_share_bar_block",
 *  admin_label = @Translation("Kwall share bar"),
 * )
 */
class KwallShareBarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label_display' => FALSE,
      'providers' => [
        'twitter' => 'twitter',
        'facebook' => 'facebook',
        'linkedin' => 'linkedin',
        'reddit' => 'reddit',
        'email' => '0',
      ],
      'use_module_css' => 1,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = \Drupal::config('kwall_share_bar.settings');
    $form['block_kwall_share_bar'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Kwall Share Bar settings'),
    ];

    $form['block_kwall_share_bar']['providers'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Main share providers'),
      '#default_value' => $this->configuration['providers'],
      '#description' => $this->t('Choose which providers you want to show in this block instance.'),
      '#options' => $config->get('providers_list'),
    ];

    $form['block_kwall_share_bar']['use_module_css'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use the css of the module.'),
      '#description' => $this->t('Disable if you want override the styles in your theme.'),
      '#default_value' => $this->configuration['use_module_css'],
    ];

    return $form;
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   *
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValue('block_kwall_share_bar');

    $this->configuration['providers'] = $values['providers'];
    $this->configuration['use_module_css'] = $values['use_module_css'];
  }

  /**
   * Implements \Drupal\block\BlockBase::blockBuild().
   *
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $enabled_providers = [];

    foreach ($this->configuration['providers'] as $key => $provider) {
      if ($provider != '0') {
        $enabled_providers[$key] = $provider;
      }
    }

    if (!empty($enabled_providers)) {
      $request = \Drupal::request();
      $route_match = \Drupal::routeMatch();
      $title = \Drupal::service('title_resolver')
        ->getTitle($request, $route_match->getRouteObject());

      if ($title == '') {
        $title = \Drupal::config('system.site')->get('name');
      }

      $build['content'] = [
        '#theme' => 'kwall_share_bar_block',
        '#providers' => $enabled_providers,
        '#url' => \Drupal::request()->getUri(),
        '#title' => Html::escape($title),
      ];
      $build['content']['#cache'] = [
        'contexts' => ['url', 'languages'],
      ];
      if ($this->configuration['use_module_css'] == 1) {
        $build['content']['#attached']['library'][] = 'kwall_share_bar/kwall_share_bar';
      }

    }

    return $build;
  }

}
