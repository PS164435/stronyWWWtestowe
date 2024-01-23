<?php
include 'admin/admin.php';

$conn = mysqli_connect('localhost', 'root', '', 'moja_strona');

EdytujPodstrone($conn, $_GET['id']);

?>
