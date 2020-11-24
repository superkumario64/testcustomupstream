<?php

namespace Drupal\Tests\html_title\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the HTML Title module.
 *
 * @group html_title
 */
class HtmlTitleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['html_title_test', 'node', 'block', 'search'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * User with administer HTML Title settings rights.
   *
   * @var \Drupal\user\Entity\User|false
   */
  protected $adminUser;

  /**
   * A node entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node1;

  /**
   * A node entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node2;

  /**
   * A node entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node3;

  /**
   * A node entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node4;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->drupalPlaceBlock('system_breadcrumb_block');
    $this->drupalPlaceBlock('page_title_block');

    $this->config('html_title.settings')
      ->set('allow_html_tags', '<br> <sub> <sup>')
      ->save();
    $this->adminUser = $this->createUser(['access content overview', 'search content']);
    $this->drupalLogin($this->adminUser);

    $this->drupalCreateContentType(['type' => 'test']);
    $this->node1 = $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag', 'type' => 'test']);
    $this->node2 = $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br>br-tag', 'type' => 'test']);
    $this->node3 = $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and <p>p</p>-tag', 'type' => 'test']);
    $this->node4 = $this->drupalCreateNode(['title' => 'Test <p>p</p>-tag', 'type' => 'test']);

    // Run a cron job so the nodes are indexed and shown on the search page.
    $this->container->get('cron')->run();
  }

  /**
   * Tests the page title block in combination with HTML Title.
   */
  public function testPageTitleBlock() {
    $this->drupalGet($this->node1->toUrl());
    $this->assertSession()->responseContains('<h1>Test <sup>sup</sup>-tag</h1>');

    $this->drupalGet($this->node2->toUrl());
    $this->assertSession()->responseContains('<h1>Test <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br>br-tag</h1>');

    $this->drupalGet($this->node3->toUrl());
    $this->assertSession()->responseContains('<h1>Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and p-tag</h1>');

    $this->drupalGet($this->node4->toUrl());
    $this->assertSession()->responseContains('<h1>Test p-tag</h1>');
  }

  /**
   * Tests the breadcrumb block in combination with HTML Title.
   */
  public function testBreadcrumbBlock() {
    $this->drupalGet($this->node1->toUrl());
    $element = $this->assertSession()->elementExists('css', 'nav[role="navigation"] ol li:last-child');
    $this->assertEquals('Test <sup>sup</sup>-tag', trim($element->getHtml()));

    $this->drupalGet($this->node2->toUrl());
    $element = $this->assertSession()->elementExists('css', 'nav[role="navigation"] ol li:last-child');
    $this->assertEquals('Test <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br>br-tag', trim($element->getHtml()));

    $this->drupalGet($this->node3->toUrl());
    $element = $this->assertSession()->elementExists('css', 'nav[role="navigation"] ol li:last-child');
    $this->assertEquals('Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and p-tag', trim($element->getHtml()));

    $this->drupalGet($this->node4->toUrl());
    $element = $this->assertSession()->elementExists('css', 'nav[role="navigation"] ol li:last-child');
    $this->assertEquals('Test p-tag', trim($element->getHtml()));
  }

  /**
   * Tests the search module in combination with HTML title.
   */
  public function testSearchPage() {
    $this->drupalPostForm(
      Url::fromRoute('search.view_node_search')->toString(),
      ['keys' => 'test br-tag p-tag'],
      'Search'
    );

    $element = $this->assertSession()->elementExists('css', 'ol li:first-child h3 a');
    $this->assertEquals('Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and p-tag', trim($element->getHtml()));
  }

}
