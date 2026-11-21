cat << 'EOF' > data/salles.php
<?php
$salles = [
    1 => [
        'id' => 1,
        'nom' => 'Salon Royal',
        'categorie' => 'Mariage & Gala',
        'capacite' => 350,
        'superficie' => 450,
        'tarif' => 1800,
        'resume' => 'Grande salle lumineuse avec scène équipée, piste de danse et terrasse privée.',
        'description' => 'Le Salon Royal offre un cadre raffiné avec hauteur sous plafond de 6 mètres, piste de danse en parquet massif, scène sonorisée, cuisine traiteur équipée et parking privé sécurisé de 120 places.',
        'formules' => [
            ['nom' => 'Pack Son & Lumière', 'tarif' => 350, 'desc' => 'Système audio professionnel, micros sans fil et jeux de lumière programmables.'],
            ['nom' => 'Décoration Florale', 'tarif' => 480, 'desc' => 'Centres de tables, arche d\'accueil et habillage complet aux couleurs de l\'événement.'],
        ],
    ],
    2 => [
        'id' => 2,
        'nom' => 'Jardin d\'Hiver',
        'categorie' => 'Réception',
        'capacite' => 150,
        'superficie' => 210,
        'tarif' => 950,
        'resume' => 'Verrière élégante idéale pour les cocktails, fiançailles et dîners intimes.',
        'description' => 'Espace baigné de lumière naturelle donnant sur un parc arboré, parfait pour les réceptions familiales, cocktails dînatoires et cérémonies privées.',
        'formules' => [
            ['nom' => 'Pack Cocktail & Ambiance', 'tarif' => 260, 'desc' => 'Éclairage chaleureux, espace bar et sonorisation douce.'],
            ['nom' => 'Décoration Florale', 'tarif' => 390, 'desc' => 'Compositions végétales et touches florales adaptées à la verrière.'],
        ],
    ],
    3 => [
        'id' => 3,
        'nom' => 'Espace Horizon',
        'categorie' => 'Séminaire',
        'capacite' => 90,
        'superficie' => 130,
        'tarif' => 600,
        'resume' => 'Salle moderne dotée d\'un vidéoprojecteur, sonorisation et accès PMR.',
        'description' => 'Salle modulable conçue pour les séminaires d\'entreprise, conférences, ateliers et assemblées avec équipement audiovisuel et accès PMR.',
        'formules' => [
            ['nom' => 'Pack Conférence Pro', 'tarif' => 220, 'desc' => 'Vidéoprojecteur haute luminosité, pupitre, micros et prise de son.'],
            ['nom' => 'Pause Café & Accueil', 'tarif' => 180, 'desc' => 'Espace d\'accueil dédié avec boissons chaudes et viennoiseries.'],
        ],
    ],
];