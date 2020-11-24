<?php

namespace Drupal\kwall_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 *
 */
class KWALLMapForm extends ConfigFormBase {

  /**
   * Store number of map overlay fields to display.
   *
   * @var
   */
  protected $map_count;

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'kwall_map.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kwall_map_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Default settings.
    $config = $this->config('kwall_map.settings');

    $form['replace_textfield_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'replace-textfield-container'],
    ];
    $form['replace_textfield_container']['num_campuses'] = [
      '#title' => $this->t("Choose the # of campus map overlay"),
      '#type' => 'select',
      '#options' => [
        '0' => 0,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
      ],
      '#default_value' => $config->get('num_campuses'),
      '#ajax' => [
        // #ajax has two required keys: callback and wrapper.
        // 'callback' is a function that will be called when this element
        // changes.
        'callback' => '::promptCallback',
        // 'wrapper' is the HTML id of the page element that will be replaced.
        'wrapper' => 'replace-textfield-container',
      ],
    ];

    $initial = $config->get('num_campuses');
    if ($initial !== NULL) {
      $this->map_count = $initial;
    }

    $updated = $form_state->getValue('num_campuses');
    // The getValue() method returns NULL by default if the form element does
    // not exist. It won't exist yet if we're building it for the first time.
    if ($updated !== NULL) {
      $this->map_count = $updated;
    }

    for ($i = 1; $i <= $this->map_count; $i++) {

      $fid = $config->get('overlay_' . $i);
      $image_url = '';
      if (isset($fid)) {
        foreach ($fid as $key => $value) {
          $image_url = '';
          $file = File::load($value);
          if ($file) {
            $image_url = ImageStyle::load('medium')
              ->buildUrl($file->getFileUri());
          }
        }
      }

      $form['replace_textfield_container']['group_' . $i] = [
        '#type' => 'details',
        '#title' => t('Campus #' . $i),
        '#open' => ($fid ? TRUE : FALSE),
        '#attributes' => ['class' => ['list-group-item']],
      ];
      $form['replace_textfield_container']['group_' . $i]['overlay_' . $i] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Campus ' . $i . ' Overlay'),
        '#description' => $this->t('Upload the map overlay'),
        '#default_value' => $fid,
        '#upload_location' => 'public://kwall_map',
      ];
      $form['replace_textfield_container']['group_' . $i]['overlay_path_' . $i] = [
        '#type' => 'textfield',
        '#maxlength' => 9999,
        '#attributes' => ['class' => ['hidden']],
        '#default_value' => ($fid ? $image_url : ''),
      ];
      $form['replace_textfield_container']['group_' . $i]['ne_lat_' . $i] = [
        '#type' => 'textfield',
        '#title' => $this->t('Campus ' . $i . ' Northeast Latitude'),
        '#description' => $this->t('Set the Northeast Latitude coordinates. Empty for default.'),
        '#default_value' => $config->get('ne_lat_' . $i),
      ];
      $form['replace_textfield_container']['group_' . $i]['ne_lon_' . $i] = [
        '#type' => 'textfield',
        '#title' => $this->t('Campus ' . $i . ' Northeast Longitude'),
        '#description' => $this->t('Set the Northeast Longitude coordinates. Empty for default.'),
        '#default_value' => $config->get('ne_lon_' . $i),
      ];
      $form['replace_textfield_container']['group_' . $i]['sw_lat_' . $i] = [
        '#type' => 'textfield',
        '#title' => $this->t('Campus ' . $i . ' Southwest Latitude'),
        '#description' => $this->t('Set the Southwest Latitude coordinates. Empty for default.'),
        '#default_value' => $config->get('sw_lat_' . $i),
      ];
      $form['replace_textfield_container']['group_' . $i]['sw_lon_' . $i] = [
        '#type' => 'textfield',
        '#title' => $this->t('Campus ' . $i . ' Southwest Longitude'),
        '#description' => $this->t('Set the Southwest Longitude coordinates. Empty for default.'),
        '#default_value' => $config->get('sw_lon_' . $i),
      ];
    }

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('kwall_map.settings');
    $config->set('num_campuses', $form_state->getValue('num_campuses'));
    for ($i = 1; $i <= $form_state->getValue('num_campuses'); $i++) {
      foreach (Element::children($form['replace_textfield_container']['group_' . $i]) as $variable) {
        $config->set($variable, $form_state->getValue($form['replace_textfield_container']['group_' . $i][$variable]['#parents']));
      }
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Handles switching the available regions based on the selected count.
   */
  public function promptCallback(array &$form, FormStateInterface $form_state) {
    return $form['replace_textfield_container'];
  }

}
