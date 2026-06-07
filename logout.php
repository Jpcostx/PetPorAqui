<?php
session_start();
session_destroy(); // Destrói a sessão (desloga)
header("Location: index.php"); // Manda de volta pra tela inicial
exit;
?>