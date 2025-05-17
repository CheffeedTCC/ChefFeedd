<?php 
$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "cheffeed";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                   $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Em produção, não mostre a mensagem de erro completa
    error_log("Connection failed: " . $e->getMessage());
    die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
}
?>