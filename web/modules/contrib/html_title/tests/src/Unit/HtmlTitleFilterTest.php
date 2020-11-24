<?php

namespace Drupal\Tests\html_title\Unit;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\Markup;
use Drupal\html_title\HtmlTitleFilter;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\html_title\HtmlTitleFilter
 *
 * @group html_title
 */
class HtmlTitleFilterTest extends UnitTestCase {

  /**
   * The availability manager.
   *
   * @var \Drupal\html_title\HtmlTitleFilter
   */
  protected $htmlTitleFilter;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config = $this->createMock(Config::class);
    $config_factory->method('get')->willReturn($config);
    $config->method('get')->willReturn('<br> <sub> <sup>');

    $this->htmlTitleFilter = new HtmlTitleFilter($config_factory);
  }

  /**
   * Tests HtmlTitleFilter::decodeToText().
   *
   * @param string $string
   *   The string passed to decodeToText().
   * @param string $expected
   *   The expected result from calling the function.
   *
   * @see HtmlTitleFilter::decodeToText()
   *
   * @dataProvider providerDecodeToText
   */
  public function testDecodeToText(string $string, string $expected) {
    $this->assertEquals($expected, $this->htmlTitleFilter->decodeToText($string));
  }

  /**
   * Data provider for testDecodeToText().
   *
   * @see testDecodeToText()
   */
  public function providerDecodeToText() {
    return [
      ['test <sup>sup</sup>-tag', 'test <sup>sup</sup>-tag'],
      ['test <p>p</p>-tag', 'test p-tag'],
      ['test &', 'test &amp;'],
      ['test without tags', 'test without tags'],
      ['test <br> br-tag', 'test <br> br-tag'],
      ['test <sub>sub</sub>-tag', 'test <sub>sub</sub>-tag'],
      ['test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag', 'test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag'],
      ['test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag <p>p</p>-tag', 'test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag p-tag'],
    ];
  }

  /**
   * Tests HtmlTitleFilter::decodeToText().
   *
   * @param string $string
   *   The Markp passed to decodeToText().
   * @param \Drupal\Core\Render\Markup $expected
   *   The expected result from calling the function.
   *
   * @see HtmlTitleFilter::decodeToMarkup()
   *
   * @dataProvider providerDecodeToMarkup
   */
  public function testDecodeToMarkup(string $string, Markup $expected) {
    $this->assertEquals($expected, $this->htmlTitleFilter->decodeToMarkup($string));
  }

  /**
   * Data provider for testDecodeToText().
   *
   * @see testDecodeToMarkup()
   */
  public function providerDecodeToMarkup() {
    return [
      ['test <sup>sup</sup>-tag', Markup::create('test <sup>sup</sup>-tag')],
      ['test <p>p</p>-tag', Markup::create('test p-tag')],
      ['test &', Markup::create('test &amp;')],
      ['test without tags', Markup::create('test without tags')],
      ['test <br> br-tag', Markup::create('test <br> br-tag')],
      ['test <sub>sub</sub>-tag', Markup::create('test <sub>sub</sub>-tag')],
      ['test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag', Markup::create('test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag and <br> br-tag')],
      ['test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br> br-tag and <p>p</p>-tag', Markup::create('test multiple tags: <sup>sup</sup>-tag, <sub>sub</sub>-tag, <br> br-tag and p-tag')],
    ];
  }

  /**
   * Tests HtmlTitleFilter::getAllowHtmlTags().
   *
   * @see HtmlTitleFilter::getAllowHtmlTags()
   */
  public function testGetAllowedHtmlTags() {
    $this->assertEquals(['br', 'sub', 'sup'], $this->htmlTitleFilter->getAllowHtmlTags());
  }

}
