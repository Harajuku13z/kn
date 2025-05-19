<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=kn', 'root', '');
    echo "Connexion réussie à la base de données\n";
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage() . "\n";
} 