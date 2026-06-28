<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);

session_start();
session_unset();
session_destroy();

header("Location: ../index.html");
exit;
?>