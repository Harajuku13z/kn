<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Capsule\Manager as DB;

try {
    // Connexion directe à la base de données
    $db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=kn;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock', 'root', '');
    
    // Générer un nouveau mot de passe hashé avec Bcrypt
    $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Mettre à jour le mot de passe de l'administrateur
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = 1");
    $stmt->execute([$hashedPassword]);
    
    echo "Mot de passe administrateur mis à jour avec succès!\n";
    echo "Email: contact@ezaklinklin.com\n";
    echo "Mot de passe: admin123\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 