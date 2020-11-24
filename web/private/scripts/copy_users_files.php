<?php

// Only run this operation for development.
if (isset($_POST['environment']) && $_POST['environment'] == 'dev') {
  // Copy user's files.
  echo "Copy user's files...\n";
  passthru('cp -r /code/install/public/* /files/');
  echo "Copying complete.\n";
}
