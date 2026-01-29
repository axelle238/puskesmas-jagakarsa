<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS puskesmas_jagakarsa");
    echo "Database puskesmas_jagakarsa siap.";
} catch (PDOException $e) {
    echo "Gagal koneksi database: " . $e->getMessage();
    exit(1);
}
