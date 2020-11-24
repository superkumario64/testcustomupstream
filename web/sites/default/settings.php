<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$config_directories['sync'] = '../config/default';

/**
 * Apply different settings for each environment.
 */
$current_environment = NULL;

// In case, if available Pantheon's environment.
if (defined('PANTHEON_ENVIRONMENT')) {
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    $current_environment = $_ENV['PANTHEON_ENVIRONMENT'];
  }
}

// In case, if available Acquia's environment.
if (isset($_ENV['AH_SITE_ENVIRONMENT'])) {
  $current_environment = $_ENV['AH_SITE_ENVIRONMENT'];
}

if ($current_environment !== NULL) {
  switch ($current_environment) {
    case 'dev':

      // Environment indicator.
      $config['environment_indicator.indicator']['bg_color'] = '#041a3d';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      $config['environment_indicator.indicator']['name'] = 'Dev';
      $config['media.settings']['iframe_domain'] = 'http://dev-ucsc.pantheonsite.io';

      // Prevent to send the emails from LOCAL.
      $settings['update_notify_emails'] = [];

      // Will take affect if the module "Reroute emails" is enabled.
      // $config['reroute_email.settings']['enable'] = TRUE;

      $config['system.performance']['css']['preprocess'] = FALSE;
      $config['system.performance']['js']['preprocess'] = FALSE;

      // Disable AdvAgg.
      $config['advagg.settings']['enabled'] = FALSE;

      // Remove it after development. Use a tariff plane instead of.
      ini_set('max_execution_time', 720);
      // ini_set('memory_limit', '8192M');

      // Verbose errors.
      $config['system.logging']['error_level'] = ERROR_REPORTING_DISPLAY_VERBOSE;

      $config['acquia_search.settings']['disable_auto_read_only'] = TRUE;

      break;

    case 'test':

      $config['environment_indicator.indicator']['bg_color'] = '#00385f';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      $config['environment_indicator.indicator']['name'] = 'Stage';
      $config['media.settings']['iframe_domain'] = 'http://test-ucsc.pantheonsite.io';

      // Prevent to send the emails from LOCAL.
      $settings['update_notify_emails'] = [];

      // Will take affect if the module "Reroute emails" is enabled.
      // $config['reroute_email.settings']['enable'] = TRUE;

      break;

    case 'prod':

      $config['environment_indicator.indicator']['bg_color'] = '#007DB1';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      $config['environment_indicator.indicator']['name'] = 'Prod';
      $config['media.settings']['iframe_domain'] = 'https://www.ucsc.edu';

      break;
  }
}
else {

  $config['environment_indicator.indicator']['bg_color'] = '#78A22F';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Local';
  $config['media.settings']['iframe_domain'] = 'http://kwall-ucsc.devel';

  // Prevent to send the emails from LOCAL.
  $settings['update_notify_emails'] = [];

  // Will take affect if the module "Reroute emails" is enabled.
  $config['reroute_email.settings']['enable'] = TRUE;

  // Verbose errors.
  $config['system.logging']['error_level'] = ERROR_REPORTING_DISPLAY_VERBOSE;
}

/**
 * Exclude a few configs from exporting/importing.
 */
$settings['config_exclude_modules'] = [
];

$conf['simplesamlphp_auth_installdir'] = $_ENV['HOME'] .'/web/private/simplesamlphp';
$settings['simplesamlphp_dir'] = $_ENV['HOME'] .'/web/private/simplesamlphp';

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 */
$settings['install_profile'] = 'standard';

/**
 * Force a sub site header/footer elements for debugging by adding
 * /?force-subsite to a request. Or always add $settings['is_subsite'] = TRUE;
 * for each sub site.
 */
$settings['is_subsite'] = FALSE;
if (isset($_GET['force-subsite'])) {
  $settings['is_subsite'] = TRUE;
}

/**
 * Uncomment this string for all subsites.
 */
// $settings['is_subsite'] = TRUE;
