<?php

namespace Drupal\FunctionalTests\Theme;

use Drupal\filter\Entity\FilterFormat;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the classy theme.
 *
 * @group classy
 */
class ClassyTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * Tests that the Classy theme always adds its message CSS.
   *
   * @see classy.info.yml
   */
  public function testRegressionMissingMessagesCss() {
    $this->drupalGet('');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('classy/css/components/messages.css');
  }

  /**
   * Tests that the classy/align library override of filter/align is loaded.
   */
  public function testFilterAlignCss() {
    $this->container
      ->get('module_installer')
      ->install(['filter', 'editor', 'ckeditor', 'node']);
    FilterFormat::create([
      'format' => 'test_format',
      'name' => 'Test format',
      'filters' => [
        'filter_align' => ['status' => TRUE],
      ],
    ])->save();
    $this->drupalCreateContentType(['type' => 'blog']);
    $node = $this->createNode([
      'type' => 'blog',
      'title' => "Compassion: that's the one things no machine ever had.",
      'body' => [
        'value' => '<blockquote data-align="center">Highly illogical.</blockquote>',
        'format' => 'test_format',
      ],
    ]);
    $node->save();
    $assert_session = $this->assertSession();
    // Test that with filter_align enabled, a page with processed text contains
    // classy's filter.align.css.
    $this->drupalGet($node->toUrl());
    $assert_session->responseContains('classy/css/components/filter.align.css');
    // Now test that without filter_align enabled, it does not.
    $filter_format = FilterFormat::load('test_format');
    $filter_format
      ->setFilterConfig('filter_align', [
        'status' => FALSE,
      ])->save();
    $this->drupalGet($node->toUrl());
    $assert_session->responseNotContains('classy/css/components/filter.align.css');
  }

}
