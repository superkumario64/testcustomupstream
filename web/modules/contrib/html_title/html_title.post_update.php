<?php

/**
 * @file
 * Post update functions for HTML Title.
 */

use Drupal\user\Entity\Role;

/**
 * Use a dedicated HTML Title permission.
 *
 * Grant users with the 'administer actions' permission also the
 * 'administer html title settings' permission.
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function html_title_post_update_provide_dedicated_html_title_settings_permission() {
  /** @var \Drupal\user\Entity\Role[] $roles */
  $roles = Role::loadMultiple();
  foreach ($roles as $role) {
    if ($role->hasPermission('administer actions')) {
      $role->grantPermission('administer html title settings');
      $role->save();
    }
  }
}
