<?php

/**
 * @file
 */

use Drupal\file\FileInterface;
use Drupal\file\Entity\File;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\media\Entity\Media;

/**
 * Hex to rgb color convert.
 *
 * @param $colour
 * @param int $op
 *
 * @return false|string
 */
function hex2rgb($colour, $op = 1) {
  if ($colour[0] == '#') {
    $colour = substr($colour, 1);
  }
  if (strlen($colour) == 6) {
    list($r, $g, $b) = [
      $colour[0] . $colour[1],
      $colour[2] . $colour[3],
      $colour[4] . $colour[5],
    ];
  }
  elseif (strlen($colour) == 3) {
    list($r, $g, $b) = [
      $colour[0] . $colour[0],
      $colour[1] . $colour[1],
      $colour[2] . $colour[2],
    ];
  }
  else {
    return FALSE;
  }
  $r = hexdec($r);
  $g = hexdec($g);
  $b = hexdec($b);

  return implode(',', [$r, $g, $b, $op]);
}

/**
 * Get Youtube Embed URL instead of Short or Watch URL.
 *
 * @param $url
 *
 * @return string|null
 */
function getYoutubeEmbedUrl($url) {
  $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
  $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
  if (preg_match($longUrlRegex, $url, $matches)) {
    $youtube_id = $matches[count($matches) - 1];
  }
  if (preg_match($shortUrlRegex, $url, $matches)) {
    $youtube_id = $matches[count($matches) - 1];
  }

  if (isset($youtube_id)) {
    return 'https://www.youtube.com/embed/' . $youtube_id;
  }

  return NULL;
}

/**
 * Get vimeo embed url.
 *
 * @param $url
 *
 * @return string
 */
function getVimeoEmbedUrl($url) {
  $regs = [];
  if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
    if ($regs[3]) {
      return "https://player.vimeo.com/video/{$regs[3]}";
    }
  }

  return $url;
}

/**
 * Get a media image by ID.
 *
 * @param $media_target_id
 *
 * @return array|false
 */
function getMediaImage($media_target_id) {
  if ($media_target_id) {
    $media = Media::load($media_target_id);
    if ($media instanceof MediaInterface && $media->hasField('field_media_image') && $file_id = $media->get('field_media_image')->target_id) {
      $file = File::load($file_id);
      if ($file instanceof FileInterface) {
        return [
          'type' => 'image',
          'url' => file_create_url($file->getFileUri()),
          'alt' => $media->get('field_media_image')->alt,
        ];
      }
    }
  }

  return FALSE;
}

/**
 * Get background image or video from a paragraph.
 *
 * @param $custom_bg_para_id
 *
 * @return array|false
 */
function getBackground($custom_bg_para_id) {
  if ($custom_bg_para_id) {

    // Switch between id and paragraph.
    if ($custom_bg_para_id instanceof ParagraphInterface) {
      $paragraph = $custom_bg_para_id;
    }
    else {
      $paragraph = Paragraph::load($custom_bg_para_id);
    }

    if ($paragraph instanceof ParagraphInterface &&
      $paragraph->hasField('field_image') &&
      $media_target_id = $paragraph->get('field_image')->target_id
    ) {
      return getMediaImage($media_target_id);
    }
    elseif ($paragraph instanceof ParagraphInterface &&
      $paragraph->hasField('field_video_embed_url') &&
      $video_url = $paragraph->get('field_video_embed_url')->value) {
      if (strpos($video_url, 'youtube') > 0) {
        return [
          'type' => 'video',
          'url' => getYoutubeEmbedUrl($video_url),
        ];
      }
      elseif (strpos($video_url, 'vimeo') > 0) {
        return [
          'type' => 'video',
          'url' => getVimeoEmbedUrl($video_url),
        ];
      }
      else {
        return $video_url;
      }
    }
  }

  return FALSE;
}

/**
 * Get Media Images.
 *
 * @param array $media_ids
 *
 * @return array|false
 */
function getMediaImages($media_ids = []) {
  if (!empty($media_ids) && is_array($media_ids)) {
    $media = [];
    foreach ($media_ids as $media_id) {
      $media[] = getMediaImage($media_id['target_id']);
    }
    return empty($media) ? FALSE : $media;
  }

  return FALSE;
}
