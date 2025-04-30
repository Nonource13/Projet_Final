<?php
// Ce script ajoute les images téléchargées aux ouvrages existants dans la base de données

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/vendor/autoload.php';

// Chargement de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Utilisation des modèles
use App\Models\Ouvrage;
use App\Models\LivreImage;
use Illuminate\Support\Facades\DB;

// Supprimer les anciennes images (pour éviter les doublons)
echo "Suppression des anciennes entrées d'images...\n";
DB::table('livre_images')->truncate();
echo "Table livre_images vidée avec succès.\n\n";

// Liste des images disponibles
$imageFiles = [
    'patisserie_facile.jpg',
    'cuisine_asiatique.jpg',
    'cuisine_vegetarienne.jpg',
    'cuisine_rapide.jpg',
    'soupes_monde.jpg',
    'cuisine_italienne.jpg',
    'default.jpg'
];

// Récupérer tous les ouvrages
$ouvrages = Ouvrage::all();
echo "Trouvé " . $ouvrages->count() . " ouvrages à mettre à jour.\n\n";

$imagesAdded = 0;

// Pour chaque ouvrage, assigner une image
foreach ($ouvrages as $index => $ouvrage) {
    // Choisir une image aléatoire, mais utiliser l'index tant qu'il y a des images disponibles
    $imageIndex = min($index, count($imageFiles) - 1);
    $imagePath = $imageFiles[$imageIndex];
    
    // Créer une nouvelle image pour l'ouvrage
    $image = new LivreImage();
    $image->ouvrage_id = $ouvrage->id;
    $image->chemin_image = $imagePath;
    $image->is_principale = true;
    
    if ($image->save()) {
        echo "Image ajoutée pour le livre '{$ouvrage->titre}': {$imagePath}\n";
        $imagesAdded++;
    } else {
        echo "Erreur lors de l'ajout de l'image pour le livre '{$ouvrage->titre}'\n";
    }
}

echo "\n-------------------------------------\n";
echo "Résumé des opérations:\n";
echo "- {$ouvrages->count()} ouvrages traités\n";
echo "- {$imagesAdded} images ajoutées avec succès\n";
echo "-------------------------------------\n\n";

echo "Les images ont été ajoutées avec succès à vos livres.\n";
echo "Vous pouvez maintenant voir les images dans votre catalogue et les pages de détail des livres.\n";
