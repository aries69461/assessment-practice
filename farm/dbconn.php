<?php
    $conn = mysqli_connect("localhost","root","","db_freshfarm");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>