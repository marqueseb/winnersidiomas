<?php
// Definir as credenciais do banco de dados
$host = "localhost";
$dbname = "contratos_db";  // Nome do banco de dados
$user = "winners";     // Usuário do PostgreSQL
$password = "123456789";   // Senha do PostgreSQL

// Conectar ao banco de dados PostgreSQL
$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Erro ao conectar ao banco de dados PostgreSQL.");
}
?>
