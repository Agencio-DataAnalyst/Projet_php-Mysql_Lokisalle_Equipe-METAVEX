<?php
require_once("inc/init.inc.php");

// Récupération des 3 dernières offres (Zone 4 du PDF)
// On s'assure que l'état est bien 'libre' (ou 0 selon ta base)
$requete = $pdo->query("SELECT p.*, s.titre, s.photo, s.ville 
                        FROM produit p 
                        INNER JOIN salle s ON p.id_salle = s.id_salle 
                        WHERE p.etat = 'libre' AND p.date_arrivee >= NOW() 
                        ORDER BY p.date_arrivee ASC LIMIT 3");

require_once("inc/header.inc.php"); 
?>

<section class="py-5 mb-5 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Bienvenue chez <span class="text-primary">LOKISALLE</span></h1>
                <p class="lead mt-3">Spécialiste de la location de salles professionnelles à Paris, Lyon et Marseille. Découvrez nos espaces modernes, équipés et prêts à l'emploi.</p>
                <div class="mt-4">
                    <a href="recherche.php" class="btn btn-primary btn-lg px-4 me-md-2 shadow-sm">Trouver une salle</a>
                    <a href="contact.php" class="btn btn-outline-secondary btn-lg px-4">Nous contacter</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded shadow-lg" alt="Espace de travail moderne">
            </div>
        </div>
    </div>
</section>

<main class="container">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Nos 3 dernières offres</h2>
                <p class="text-muted">Les créneaux les plus proches de chez vous</p>
            </div>
            <a href="recherche.php" class="text-primary text-decoration-none fw-bold">Voir toutes les offres <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row">
            <?php if($requete->rowCount() > 0): ?>
                <?php while($p = $requete->fetch(PDO::FETCH_ASSOC)): 
                    // GESTION DE L'IMAGE : On vérifie si le fichier existe physiquement
                    $image_nom = !empty($p['photo']) ? $p['photo'] : 'default.jpg';
                    $image_path = "photo/" . $image_nom;
                    
                    // Si le fichier n'existe pas sur le serveur (Render), on utilise une image par défaut
                    if (!file_exists($image_path)) {
                        $image_display = "https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=400&q=60";
                    } else {
                        $image_display = $image_path;
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-0 card-offer" style="transition: transform 0.3s; border-radius: 15px; overflow: hidden;">
                            <div class="position-relative">
                                <img src="<?= $image_display ?>" class="card-img-top" alt="<?= $p['titre'] ?>" style="height:220px; object-fit:cover;">
                                
                                <div class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 fs-6 fw-bold shadow">
                                    <?= number_format($p['prix'] * 1.2, 0, ',', ' ') ?> € TTC
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?= $p['titre'] ?></h5>
                                    <span class="text-muted small"><i class="bi bi-geo-alt-fill text-danger"></i> <?= $p['ville'] ?></span>
                                </div>
                                <div class="card-text text-muted small bg-light p-2 rounded">
                                    <div class="mb-1"><i class="bi bi-calendar3 me-2"></i>Du <strong><?= date('d/m/Y', strtotime($p['date_arrivee'])) ?></strong></div>
                                    <div><i class="bi bi-calendar3-fill me-2"></i>Au <strong><?= date('d/m/Y', strtotime($p['date_depart'])) ?></strong></div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-outline-primary w-100 rounded-pill">Détails de l'offre</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info py-5 text-center shadow-sm">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                        <h4 class="fw-bold">Aucune salle disponible</h4>
                        <p class="mb-0">Toutes nos salles sont actuellement réservées. Revenez très bientôt !</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-5 text-center bg-white rounded shadow-sm mb-5 border">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3">
                    <i class="bi bi-shield-check fs-1 text-primary"></i>
                    <h5 class="fw-bold mt-3">Paiement Sécurisé</h5>
                    <p class="text-muted small">Réservation garantie et transactions protégées.</p>
                </div>
            </div>
            <div class="col-md-4 border-start border-end">
                <div class="p-3">
                    <i class="bi bi-clock-history fs-1 text-primary"></i>
                    <h5 class="fw-bold mt-3">Disponibilité 24/7</h5>
                    <p class="text-muted small">Réservez votre espace de travail à n'importe quel moment.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <i class="bi bi-chat-dots fs-1 text-primary"></i>
                    <h5 class="fw-bold mt-3">Support Client</h5>
                    <p class="text-muted small">Une équipe à votre écoute pour vous accompagner.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    /* Effet au survol des cartes */
    .card-offer:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
</style>

<?php require_once("inc/footer.inc.php"); ?>