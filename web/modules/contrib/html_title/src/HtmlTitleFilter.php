<?php

namespace Drupal\html_title;

use Drupal\Component\Utility\Xss;
use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\Markup;

/**
 * Drupal\html_titleHtmlTitleFilter.
 */
class HtmlTitleFilter {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * HtmlTitleFilter constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * Helper function to help filter out unwanted XSS opportunities.
   *
   * Use this function if you expect to have junk or incomplete html. It uses
   * the same strategy as the "Fix Html" filter option in configuring the HTML
   * filter in the text format configuration.
   */
  protected function filterXss($title) {
    $dom = new \DOMDocument();
    // Ignore warnings during HTML soup loading.
    @$dom->loadHTML('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>' . $title . '</body></html>', LIBXML_NOENT);
    $xp = new \DOMXPath($dom);
    $q = "//body//text()";
    $nodes = $xp->query($q);

    foreach ($nodes as $n) {
      $n->nodeValue = htmlspecialchars($n->nodeValue, ENT_QUOTES);
    }
    $body = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
    // $dom->saveHTML() escapes & as &amp; for all entities that were replaced
    // using htmlspecialchars(). Undo this double-escaping.
    $body = str_replace('&amp;', '&', $body);

    return Xss::filter($body, $this->getAllowHtmlTags());
  }

  /**
   * Filte string with allow html tags.
   */
  public function decodeToText($str) {
    return $this->filterXss(Html::decodeEntities((string) $str));
  }

  /**
   * Filte string with allow html tags.
   */
  public function decodeToMarkup($str) {
    return Markup::create($this->decodeToText($str));
  }

  /**
   * Get allow html tags array.
   */
  public function getAllowHtmlTags() {
    $tags = [];
    $html = str_replace('>', ' />', $this->configFactory->get('html_title.settings')->get('allow_html_tags'));

    $body_child_nodes = Html::load($html)->getElementsByTagName('body')->item(0)->childNodes;

    foreach ($body_child_nodes as $node) {
      if ($node->nodeType === XML_ELEMENT_NODE) {
        $tags[] = $node->tagName;
      }
    }

    return $tags;
  }

}
