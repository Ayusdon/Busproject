<?php
// ===== Session start and destroy========
session_start();
session_unset();
session_destroy();

// ======= when user logs out, redirect to login.php, destroying the session =======
header("Location:../login.php");
exit();
?>
