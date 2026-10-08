<?php
session_start();
session_unset();
session_destroy();
header("Location: /gold-shop/auth/login.php");
exit;