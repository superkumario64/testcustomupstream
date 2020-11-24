<?php

// Only run this operation for development.
if (isset($_POST['environment']) && $_POST['environment'] == 'dev') {

  // Import DB.
  echo "Drop database...\n";
  passthru('drush sql-drop -y');
  echo "Dropping complete.\n";

  echo "Import database...\n";
  passthru('drush sql-cli < /code/install/database/install.sql');
  echo "Importing complete.\n";

  echo "Clear the cache after importing of the database...\n";
  passthru('drush cr');
  echo "Clearing complete.\n";
}
