<?php
// ===== Session start and destroy========
session_start();
session_unset();
session_destroy();
// ======= when  actor logout redirect to login.php destroying sesson =======
header("Location: ../login.php");
?>
