<?php
// Vérifier si l'extension GD est installée
if (!extension_loaded('gd')) {
    die('L\'extension GD n\'est pas installée. Veuillez l\'installer pour générer les PNG.');
}

// Créer une nouvelle image
$image = imagecreatetruecolor(512, 512);

// Définir les couleurs
$violet = imagecolorallocate($image, 74, 20, 140); // #4A148C
$blanc = imagecolorallocate($image, 255, 255, 255);

// Remplir le fond avec le violet
imagefilledellipse($image, 256, 256, 512, 512, $violet);

// Dessiner le K stylisé
$points = array(
    256, 128,  // Point haut
    320, 256,  // Point droit
    256, 384,  // Point bas
    192, 256   // Point gauche
);
imagefilledpolygon($image, $points, 4, $blanc);

// Dessiner la ligne horizontale
imagesetthickness($image, 32);
imageline($image, 192, 256, 320, 256, $blanc);

// Ajouter un effet de brillance
$gradient = imagecreatetruecolor(512, 512);
imagefilledellipse($gradient, 256, 256, 400, 400, $blanc);
imagecopymerge($image, $gradient, 0, 0, 0, 0, 512, 512, 10);

// Sauvegarder l'image
imagepng($image, 'safari-pinned-tab.png', 9);

// Libérer la mémoire
imagedestroy($image);
imagedestroy($gradient);

echo "Le fichier safari-pinned-tab.png a été généré avec succès !";
?> 