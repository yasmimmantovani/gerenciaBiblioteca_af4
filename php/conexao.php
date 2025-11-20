<?php
// php/conexao.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "clubelivro";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Erro ao conectar ao banco: " . $mysqli->connect_error);
}

// opcional: define charset
$mysqli->set_charset("utf8mb4");
