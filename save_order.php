<?php
    include "dbconn.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $request_raw = file_get_contents("php://input");
        $request = json_decode($request_raw);

        $role = $request->role; // added role
        $password = $request->password . "phenix";
        $cipher = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO `tbl_users`(`username`, `password`,`role`) 
        VALUES ('$request->username','$cipher','$role')";

        $conn->query($sql);

        $data = [
            "status" => "success",
            "user" => $request->username
        ];
        echo json_encode($data);
        
        // // DECRYPTION for login
        // // 12345
        // $password = $request->password . "phenix"; // salt
        // $decrypted_password = password_verify($password, $cipher);
    }

?>