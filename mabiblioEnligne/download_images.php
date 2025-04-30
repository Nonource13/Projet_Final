<?php
// Script pour télécharger des images exemple de livres de cuisine

// Création du dossier s'il n'existe pas
$dir = __DIR__ . '/public/images/livres';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
    echo "Dossier images/livres créé\n";
}

// Créer une image par défaut si elle n'existe pas
$defaultImagePath = $dir . '/default.jpg';
if (!file_exists($defaultImagePath)) {
    // Création d'une image par défaut
    $img = imagecreatetruecolor(300, 400);
    $bg = imagecolorallocate($img, 240, 240, 240);
    $textColor = imagecolorallocate($img, 50, 50, 50);
    
    // Remplir l'arrière-plan
    imagefill($img, 0, 0, $bg);
    
    // Ajouter du texte
    $text = "Livre Gourmand";
    $fontSize = 5;
    $x = (300 - strlen($text) * imagefontwidth($fontSize)) / 2;
    $y = 200 - imagefontheight($fontSize) / 2;
    
    imagestring($img, $fontSize, $x, $y, $text, $textColor);
    
    // Sauvegarder l'image
    imagejpeg($img, $defaultImagePath, 90);
    imagedestroy($img);
    
    echo "Image par défaut créée: {$defaultImagePath}\n";
}

// URLs des images de livres de cuisine (à remplacer par des URLs réelles)
$imageUrls = [
    [
        'url' => 'https://images.unsplash.com/photo-1589985270958-638aeacbf8c5',
        'filename' => 'cuisine_francaise.jpg',
        'titre' => 'Cuisine Française Traditionnelle',
        'auteur' => 'Jean Martin'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38',
        'filename' => 'patisserie_facile.jpg',
        'titre' => 'Pâtisserie Facile',
        'auteur' => 'Marie Dupont'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1535007729440-499fcef7dc58',
        'filename' => 'desserts_gourmands.jpg',
        'titre' => 'Desserts Gourmands',
        'auteur' => 'Pierre Durand'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1509358271058-acd22cc93898',
        'filename' => 'cuisine_asiatique.jpg',
        'titre' => 'Secrets de la Cuisine Asiatique',
        'auteur' => 'Sophie Chen'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71',
        'filename' => 'cuisine_vegetarienne.jpg',
        'titre' => 'Cuisine Végétarienne',
        'auteur' => 'Lucie Verte'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1556269923-e4ef51d69638',
        'filename' => 'cuisine_rapide.jpg',
        'titre' => 'Repas en 30 Minutes',
        'auteur' => 'Thomas Rapide'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543',
        'filename' => 'soupes_monde.jpg',
        'titre' => 'Soupes du Monde',
        'auteur' => 'Claire Dubois'
    ],
    [
        'url' => 'https://images.unsplash.com/photo-1517686469429-8bdb88b9f907',
        'filename' => 'cuisine_italienne.jpg',
        'titre' => 'Cuisine Italienne Authentique',
        'auteur' => 'Marco Rossi'
    ]
];

// Télécharger les images
foreach ($imageUrls as $key => $imageData) {
    $imagePath = $dir . '/' . $imageData['filename'];
    
    if (!file_exists($imagePath)) {
        try {
            // Télécharger l'image
            $imageContent = @file_get_contents($imageData['url']);
            if ($imageContent !== false) {
                file_put_contents($imagePath, $imageContent);
                echo "Image téléchargée: {$imagePath}\n";
            } else {
                echo "Erreur: Impossible de télécharger {$imageData['url']}\n";
            }
        } catch (Exception $e) {
            echo "Erreur: {$e->getMessage()}\n";
        }
    } else {
        echo "L'image {$imagePath} existe déjà\n";
    }
}

// Créer une liste d'images en format JSON pour référence
$imageJson = json_encode(array_map(function($img) {
    return [
        'filename' => $img['filename'],
        'titre' => $img['titre'],
        'auteur' => $img['auteur']
    ];
}, $imageUrls), JSON_PRETTY_PRINT);

file_put_contents(__DIR__ . '/public/images/livres/images.json', $imageJson);
echo "Fichier JSON des images créé\n";

// Script pour mettre à jour la base de données - préparation
echo "\nVoici le code SQL pour mettre à jour votre base de données avec ces images :\n";
echo "---------------------------------------------------------------------------\n";

foreach ($imageUrls as $key => $imageData) {
    $id = $key + 1;
    echo "-- Mise à jour de l'ouvrage : {$imageData['titre']}\n";
    echo "INSERT INTO livre_images (ouvrage_id, chemin, created_at, updated_at) VALUES ($id, '{$imageData['filename']}', NOW(), NOW());\n\n";
}

echo "---------------------------------------------------------------------------\n";
echo "Vous pouvez exécuter ces requêtes dans votre gestionnaire de base de données pour associer les images aux ouvrages.\n";
echo "N'oubliez pas d'ajuster les ouvrage_id en fonction de vos données existantes.\n";

echo "\nOpération terminée.";
