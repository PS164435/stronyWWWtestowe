<?php
include 'admin/admin.php';

$conn = mysqli_connect('localhost', 'root', '', 'moja_strona');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    EdytujPodstrone($conn, $id);
} else {
    echo "Brak ID podstrony do edycji.";
}
?>
