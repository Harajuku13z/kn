<?php
// Tenter la connexion sans mot de passe
try {
    $pdo1 = new PDO('mysql:host=127.0.0.1;dbname=kn', 'root', '');
    echo "Connexion SANS mot de passe réussie.\n";
} catch (PDOException $e) {
    echo "Erreur de connexion SANS mot de passe: " . $e->getMessage() . "\n";
}

// Tenter la connexion avec le mot de passe 'root'
try {
    $pdo2 = new PDO('mysql:host=127.0.0.1;dbname=kn', 'root', 'root');
    echo "Connexion AVEC mot de passe 'root' réussie.\n";
} catch (PDOException $e) {
    echo "Erreur de connexion AVEC mot de passe 'root': " . $e->getMessage() . "\n";
}

// Afficher les variables d'environnement définies dans Laravel
echo "\nVariables d'environnement Laravel relatives à la base de données:\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'non défini') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'non défini') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'non défini') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'non défini') . "\n";
echo "DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'non défini') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ?: 'non défini') . "\n"; 