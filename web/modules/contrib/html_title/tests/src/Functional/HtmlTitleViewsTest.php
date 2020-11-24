<?php

namespace Drupal\Tests\html_title\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the HTML Title integration with Views.
 *
 * @group html_title
 */
class HtmlTitleViewsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['html_title', 'views', 'node'];

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
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->config('html_title.settings')
      ->set('allow_html_tags', '<br> <sub> <sup>')
      ->save();
    $this->adminUser = $this->createUser(['access content overview']);
    $this->drupalLogin($this->adminUser);

    $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag']);
    $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br>br-tag']);
    $this->drupalCreateNode(['title' => 'Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and <p>p</p>-tag']);
    $this->drupalCreateNode(['title' => 'Test <p>p</p>-tag']);
  }

  /**
   * Tests the HTML Title views integration.
   */
  public function testViewsIntegration() {
    $this->drupalGet(Url::fromRoute('system.admin_content'));

    $assert_session = $this->assertSession();
    $assert_session->responseContains('Test <sup>sup</sup>-tag');
    $assert_session->responseContains('Test <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br>br-tag');
    $assert_session->responseContains('Test <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br>br-tag and p-tag');
    $assert_session->responseNotContains('Test <p>p</p>-tag');
    $assert_session->responseContains('Test p-tag');
  }

  /**
   * Tests the output when the views style RSS is used.
   */
  public function testViewsRssStyle() {
    $this->drupalGet('rss.xml');

    $assert_session = $this->assertSession();
    $assert_session->responseContains('<title>Test sup-tag</title>');
    $assert_session->responseContains('<title>Test sup-tag, sub-tag and br-tag</title>');
    $assert_session->responseContains('<title>Test sup-tag, sub-tag, br-tag and p-tag</title>');
    $assert_session->responseContains('<title>Test p-tag</title>');
  }

}
