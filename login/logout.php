<?php
  session_start();
  if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    header("Location: ../index.php");
    exit;
  }
?>
