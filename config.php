<?php
// 数据库配置信息
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'energy';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
