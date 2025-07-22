<?php
    $conn = mysqli_connect("localhost","freshfarm","freshfarm","db_freshfarm", "3307");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>