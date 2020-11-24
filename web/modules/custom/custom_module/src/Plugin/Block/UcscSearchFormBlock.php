<?php

namespace Drupal\custom_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a 'UCSCSearchFormBlock' Block.
 *
 * @Block(
 *   id = "ucsc_search_form",
 *   admin_label = @Translation("UCSC Search Form Block"),
 *   category = @Translation("UCSC Custom Blocks"),
 * )
 */
class UcscSearchFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request|null
   */
  protected $request;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $requestStack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $requestStack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $keyword = $this->request->get('q');

    return [
      '#type' => 'inline_template',
      '#template' => "<div class=\"global-search-container off-canvas position-top align-center is-transition-overlap is-closed\" id=\"global-search\" data-off-canvas=\"huvge0-off-canvas\" aria-hidden=\"false\">
                          <div class=\"global-search\">
                               <a href=\"javascript:void(0)\" class=\"close-modal\" data-toggle=\"global-search\" aria-expanded=\"true\" aria-controls=\"global-search\">
                                  <i class=\"fa fa-times\" aria-hidden=\"true\"></i>
                               </a>
                               <h2 class=\"mt-25\">{{ screen_reader_name }}</h2>
                          <form action='/search-results'>
                              <div class=\"input-group\">
                                <input class=\"input-group-field\" name='q' type=\"search\" placeholder=\"Search this site\" id=\"searchInput\" value='{{ value }}'>
                                 <div class=\"input-group-button\">
                                   <a class=\"button secondary\" href=\"javascript:void(0)\"><i class=\"fas fa-search\"></i> <span class=\"show-for-large\">Search</span></a>
                                </div>
                             </div>
                          </form>
                          <div class=\"footer-links\">Other ways to search: <a href=\"javascript:void(0)\" aria-label=\"\">People</a> | <a href=\"javascript:void(0)\" aria-label=\"\">A-Z Index</a> | <a href=\"javascript:void(0)\" aria-label=\"\">Calendars</a>
                        </div>
                       </div>            
                     </div>
        ",
      '#context' => [
        'screen_reader_name' => 'Search UCSC:',
        'value' => $keyword,
      ],
    ];
  }

}
