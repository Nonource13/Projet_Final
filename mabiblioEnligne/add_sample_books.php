<?php
// Ce script ajoute 15 livres de cuisine avec leurs images dans la base de données
require_once __DIR__ . '/vendor/autoload.php';

// Chargement de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Utiliser les modèles
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\LivreImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// Vérifier si des catégories existent, sinon en créer
$categories = Categorie::all();
if ($categories->isEmpty()) {
    echo "Création des catégories de base...\n";
    $defaultCategories = [
        'Cuisine française', 
        'Pâtisserie', 
        'Cuisine asiatique', 
        'Cuisine végétarienne', 
        'Cuisine rapide',
        'Cuisine méditerranéenne',
        'Desserts',
        'Cuisine du monde'
    ];
    
    foreach ($defaultCategories as $cat) {
        Categorie::create(['nom' => $cat]);
    }
    $categories = Categorie::all();
    echo count($categories) . " catégories créées.\n";
}

// Informations sur 15 livres de cuisine
$books = [
    [
        'titre' => 'L\'Art de la Cuisine Française',
        'auteur' => 'Marie Dupont',
        'description' => 'Ce livre explore les techniques fondamentales de la cuisine française classique, avec des recettes traditionnelles et leurs histoires. Des explications détaillées sur les sauces mères, les cuissons et les présentations.',
        'niveau_expertise' => 'amateur',
        'prix' => 39.99,
        'categorie' => 'Cuisine française',
        'date_publication' => '2023-05-15',
        'image' => 'cuisine_francaise.jpg'
    ],
    [
        'titre' => 'Pâtisserie pour Débutants',
        'auteur' => 'Jean Martin',
        'description' => 'Un guide pas à pas pour maîtriser l\'art de la pâtisserie française. Des recettes simples et accessibles pour débuter, avec des conseils pratiques sur les techniques de base.',
        'niveau_expertise' => 'débutant',
        'prix' => 24.95,
        'categorie' => 'Pâtisserie',
        'date_publication' => '2023-01-20',
        'image' => 'patisserie_facile.jpg'
    ],
    [
        'titre' => 'Secrets des Chefs Pâtissiers',
        'auteur' => 'Sophie Leclerc',
        'description' => 'Découvrez les secrets des grands chefs pâtissiers français. Ce livre contient des recettes avancées et des techniques professionnelles pour créer des desserts spectaculaires.',
        'niveau_expertise' => 'chef',
        'prix' => 42.50,
        'categorie' => 'Pâtisserie',
        'date_publication' => '2022-11-10',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Cuisine Asiatique Authentique',
        'auteur' => 'Lin Zhang',
        'description' => 'Un voyage culinaire à travers l\'Asie, avec des recettes traditionnelles de Chine, Japon, Thaïlande et Vietnam. Apprenez à utiliser les ingrédients et techniques spécifiques à ces cuisines.',
        'niveau_expertise' => 'amateur',
        'prix' => 29.99,
        'categorie' => 'Cuisine asiatique',
        'date_publication' => '2023-03-30',
        'image' => 'cuisine_asiatique.jpg'
    ],
    [
        'titre' => 'Recettes Végétariennes du Monde',
        'auteur' => 'Emma Green',
        'description' => 'Plus de 100 recettes végétariennes inspirées des cuisines du monde entier. Des plats savoureux et équilibrés pour tous les jours.',
        'niveau_expertise' => 'débutant',
        'prix' => 27.50,
        'categorie' => 'Cuisine végétarienne',
        'date_publication' => '2023-02-15',
        'image' => 'cuisine_vegetarienne.jpg'
    ],
    [
        'titre' => 'Dîners en 30 Minutes',
        'auteur' => 'Thomas Rapide',
        'description' => 'Des recettes rapides et délicieuses pour les soirs de semaine. Tous les plats peuvent être préparés en 30 minutes ou moins, sans compromettre la saveur.',
        'niveau_expertise' => 'débutant',
        'prix' => 22.99,
        'categorie' => 'Cuisine rapide',
        'date_publication' => '2023-06-10',
        'image' => 'cuisine_rapide.jpg'
    ],
    [
        'titre' => 'La Bible des Soupes',
        'auteur' => 'Claire Dubois',
        'description' => 'Une collection complète de recettes de soupes du monde entier, pour chaque saison. Des potages classiques aux créations modernes, en passant par les bouillons et consommés.',
        'niveau_expertise' => 'débutant',
        'prix' => 25.99,
        'categorie' => 'Cuisine du monde',
        'date_publication' => '2022-09-20',
        'image' => 'soupes_monde.jpg'
    ],
    [
        'titre' => 'Cuisine Italienne Traditionnelle',
        'auteur' => 'Marco Rossi',
        'description' => 'Les recettes authentiques de la cuisine italienne familiale. Pâtes, pizzas, risottos et desserts traditionnels expliqués par un chef italien.',
        'niveau_expertise' => 'amateur',
        'prix' => 32.50,
        'categorie' => 'Cuisine méditerranéenne',
        'date_publication' => '2023-04-05',
        'image' => 'cuisine_italienne.jpg'
    ],
    [
        'titre' => 'Desserts Gourmands Sans Sucre',
        'auteur' => 'Julie Sante',
        'description' => 'Des recettes de desserts délicieux sans sucre ajouté. Idéal pour les personnes surveillant leur consommation de sucre ou suivant un régime spécifique.',
        'niveau_expertise' => 'amateur',
        'prix' => 26.99,
        'categorie' => 'Desserts',
        'date_publication' => '2023-01-15',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'L\'Art du Pain Maison',
        'auteur' => 'Pierre Boulanger',
        'description' => 'Tout ce qu\'il faut savoir pour réussir son pain maison. Des techniques de pétrissage, fermentation et cuisson aux recettes de base et avancées.',
        'niveau_expertise' => 'chef',
        'prix' => 35.00,
        'categorie' => 'Pâtisserie',
        'date_publication' => '2022-08-25',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Cuisine Méditerranéenne Santé',
        'auteur' => 'Elena Martinez',
        'description' => 'Les bienfaits de la cuisine méditerranéenne dans votre assiette. Des recettes équilibrées inspirées de la Grèce, l\'Italie et l\'Espagne.',
        'niveau_expertise' => 'débutant',
        'prix' => 28.50,
        'categorie' => 'Cuisine méditerranéenne',
        'date_publication' => '2023-05-20',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Le Grand Livre des Cocktails',
        'auteur' => 'Alexandre Barman',
        'description' => 'Plus de 500 recettes de cocktails classiques et créations originales. Techniques de mixologie, présentation et accords mets-cocktails.',
        'niveau_expertise' => 'amateur',
        'prix' => 34.99,
        'categorie' => 'Cuisine du monde',
        'date_publication' => '2022-12-10',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Cuisine Fusion Moderne',
        'auteur' => 'David Chen',
        'description' => 'Un mélange créatif de techniques et saveurs de différentes cultures culinaires. Des recettes innovantes qui combinent tradition et modernité.',
        'niveau_expertise' => 'chef',
        'prix' => 38.50,
        'categorie' => 'Cuisine du monde',
        'date_publication' => '2023-03-15',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Chocolats et Confiseries',
        'auteur' => 'Marie Chocolat',
        'description' => 'L\'art de la chocolaterie et de la confiserie expliqué par une experte. Techniques de tempérage, moulage et création de bonbons artisanaux.',
        'niveau_expertise' => 'chef',
        'prix' => 40.00,
        'categorie' => 'Desserts',
        'date_publication' => '2022-10-30',
        'image' => 'default.jpg'
    ],
    [
        'titre' => 'Petit-déjeuners du Monde',
        'auteur' => 'Laura Matin',
        'description' => 'Découvrez comment le monde entier commence sa journée. Des recettes de petits-déjeuners traditionnels de différents pays et cultures.',
        'niveau_expertise' => 'débutant',
        'prix' => 23.99,
        'categorie' => 'Cuisine du monde',
        'date_publication' => '2023-02-28',
        'image' => 'default.jpg'
    ],
];

// Ajouter les livres à la base de données
$booksAdded = 0;
$imagesAdded = 0;

foreach ($books as $book) {
    // Vérifier si le livre existe déjà
    $existingBook = Ouvrage::where('titre', $book['titre'])->first();
    if ($existingBook) {
        echo "Le livre '{$book['titre']}' existe déjà. Ignoré.\n";
        continue;
    }
    
    // Trouver la catégorie ou utiliser la première disponible
    $categorie = Categorie::where('nom', $book['categorie'])->first();
    if (!$categorie) {
        $categorie = $categories->first();
        echo "Catégorie '{$book['categorie']}' non trouvée. Utilisation de '{$categorie->nom}' à la place.\n";
    }
    
    // Créer le nouvel ouvrage
    $ouvrage = new Ouvrage([
        'titre' => $book['titre'],
        'auteur' => $book['auteur'],
        'description' => $book['description'],
        'niveau_expertise' => $book['niveau_expertise'],
        'prix' => $book['prix'],
        'date_publication' => $book['date_publication'],
        'categorie_id' => $categorie->id
    ]);
    
    $ouvrage->save();
    $booksAdded++;
    
    // Associer l'image si elle existe
    $imagePath = $book['image'];
    
    // Vérifier si l'image existe dans le dossier
    if (file_exists(public_path('images/livres/' . $imagePath))) {
        $livreImage = new LivreImage([
            'ouvrage_id' => $ouvrage->id,
            'chemin_image' => $imagePath,
            'is_principale' => true
        ]);
        
        $livreImage->save();
        $imagesAdded++;
        
        echo "Livre '{$book['titre']}' ajouté avec l'image '{$imagePath}'.\n";
    } else {
        echo "Livre '{$book['titre']}' ajouté sans image (fichier '{$imagePath}' introuvable).\n";
    }
}

echo "\n===== RÉSUMÉ =====\n";
echo "{$booksAdded} nouveaux livres ajoutés à la base de données.\n";
echo "{$imagesAdded} images associées aux livres.\n";
echo "=================\n";

// Afficher le nombre total d'ouvrages dans la base de données
$totalBooks = Ouvrage::count();
echo "\nNombre total d'ouvrages dans la base de données: {$totalBooks}\n";
