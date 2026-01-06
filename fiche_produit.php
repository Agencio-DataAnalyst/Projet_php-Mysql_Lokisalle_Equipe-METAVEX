<?php
require_once("inc/init.inc.php");

// 1. Sécurisation de l'ID reçu
if(!isset($_GET['id_produit']) || !is_numeric($_GET['id_produit'])) {
    header("location:index.php");
    exit();
}

// 2. Requête sécurisée (Requête préparée)
$resultat = $pdo->prepare("SELECT s.*, p.* FROM salle s 
                           INNER JOIN produit p ON s.id_salle = p.id_salle 
                           WHERE p.id_produit = :id");
$resultat->execute([':id' => $_GET['id_produit']]);
$produit = $resultat->fetch(PDO::FETCH_ASSOC);

// 3. Vérification de l'existence et de l'état
if(!$produit || $produit['etat'] == 'reserve') {
    header("location:index.php");
    exit();
}

// Gestion de l'image pour Render
$image_path = "photo/" . $produit['photo'];
$src_image = (!empty($produit['photo']) && file_exists($image_path)) ? $image_path : "https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80";

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item active"><?= $produit['titre'] ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3 text-dark"><?= $produit['titre'] ?></h1>
            <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> <?= $produit['adresse'] ?>, <?= $produit['cp'] ?> <?= $produit['ville'] ?></p>
            
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <img src="<?= $src_image ?>" class="img-fluid" alt="<?= $produit['titre'] ?>" style="width: 100%; height: 450px; object-fit: cover;">
            </div>
            
            <h4 class="fw-bold mt-4 border-bottom pb-2">Description de la salle</h4>
            <p class="text-muted leading-relaxed" style="font-size: 1.1rem;"><?= nl2br($produit['description']) ?></p>
            
            <div class="row text-center mt-5 g-3">
                <div class="col-md-4">
                    <div class="p-3 bg-white shadow-sm rounded">
                        <i class="bi bi-people fs-3 text-primary"></i>
                        <p class="small text-uppercase text-muted mb-0">Capacité</p>
                        <strong class="fs-5"><?= $produit['capacite'] ?> personnes</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white shadow-sm rounded">
                        <i class="bi bi-tag fs-3 text-primary"></i>
                        <p class="small text-uppercase text-muted mb-0">Catégorie</p>
                        <strong class="fs-5"><?= ucfirst($produit['categorie']) ?></strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white shadow-sm rounded">
                        <i class="bi bi-building fs-3 text-primary"></i>
                        <p class="small text-uppercase text-muted mb-0">Ville</p>
                        <strong class="fs-5"><?= ucfirst($produit['ville']) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 sticky-top" style="top: 100px; border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-center mb-4">Votre Réservation</h5>
                    
                    <div class="mb-4 bg-light p-3 rounded">
                        <div class="mb-3">
                            <label class="small text-uppercase fw-bold text-muted">Arrivée</label>
                            <div class="fs-6"><i class="bi bi-calendar-check text-primary me-2"></i><?= date('d/m/Y à H:i', strtotime($produit['date_arrivee'])) ?></div>
                        </div>
                        
                        <div class="mb-0">
                            <label class="small text-uppercase fw-bold text-muted">Départ</label>
                            <div class="fs-6"><i class="bi bi-calendar-x text-primary me-2"></i><?= date('d/m/Y à H:i', strtotime($produit['date_depart'])) ?></div>
                        </div>
                    </div>
                    
                    <div class="text-center my-4">
                        <span class="display-5 fw-bold text-dark"><?= number_format($produit['prix'] * 1.2, 2, ',', ' ') ?> €</span>
                        <p class="text-muted small">Prix total TTC (TVA 20%)</p>
                    </div>

                    <?php if(estConnecte()) : ?>
                        <form action="reservation.php" method="POST">
                            <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow fw-bold">
                                Confirmer la réservation
                            </button>
                        </form>
                    <?php else : ?>
                        <div class="alert alert-warning border-0 text-center small">
                            Identifiez-vous pour louer cet espace.
                        </div>
                        <a href="connexion.php" class="btn btn-dark w-100 mb-2">Se connecter</a>
                        <a href="inscription.php" class="btn btn-link btn-sm w-100 text-decoration-none">Pas encore de compte ?</a>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 text-center pb-4">
                    <a href="index.php" class="text-decoration-none small text-primary"><i class="bi bi-arrow-left"></i> Retour aux salles</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>