<?php
// Replace passwords with the ones you want to hash
echo 'admin@library.com: ' . password_hash('admin123', PASSWORD_DEFAULT) . '<br>';
echo 'noureldien@gmail.com: ' . password_hash('noureldien123', PASSWORD_DEFAULT) . '<br>';
echo 'soltan@gmail.com: ' . password_hash('soltan123', PASSWORD_DEFAULT) . '<br>';
?>
