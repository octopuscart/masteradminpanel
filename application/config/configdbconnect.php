<?php
$globleConnectDB = array();
try {
    $username = "j2k5e6r5_octopus";
    $password = "India$2017";
<<<<<<< HEAD
    $conn = new PDO('mysql:host=localhost;dbname=j2k5e6r5_costcokart', $username, $password);
=======
    $conn = new PDO('mysql:host=localhost;dbname=j2k5e6r5_octpuscartshop', $username, $password);
>>>>>>> 682f5af3f2886a27009f79d19fe9edbb1b8f9500
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    $stmt = $conn->prepare('SELECT * FROM configuration_site');
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $globleConnectDB = $row;
    }
    
    $stmt = $conn->prepare('SELECT * FROM configuration_report');
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $globleConnectReport = $row;
    }
    
    
} catch(PDOException $e) {
 
}
