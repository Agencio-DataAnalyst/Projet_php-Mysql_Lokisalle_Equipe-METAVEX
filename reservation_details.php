<?php 
require_once("inc/init.inc.php"); 

// 1. Vérification de l'ID
if(!isset($_GET['id_produit']) || !is_numeric($_GET['id_produit'])) { 
    header("location:index.php"); 
    exit(); 
}

// 2. Récupération des données avec Jointure
$res = $pdo->prepare("SELECT p.*, s.* FROM produit p JOIN salle s ON p.id_salle = s.id_salle WHERE p.id_produit = ?");
$res->execute([$_GET['id_produit']]);
$p = $res->fetch(PDO::FETCH_ASSOC);

// Si le produit n'existe pas
if(!$p) { header("location:index.php"); exit(); }

// 3. Calcul du prix TTC (Page 10 du PDF d'examen)
$prix_ht = $p['prix'];
$tva = $prix_ht * 0.20;
$prix_ttc = $prix_ht + $tva;

// 4. Gestion de l'image pour Render
$image_path = "photo/" . $p['photo'];
$src_image = (!empty($p['photo']) && file_exists($image_path)) ? $image_path : "photo/default.jpg";

require_once("inc/header.inc.php"); 
?>

<div class="container py-5">
    <div class="row bg-white p-5 shadow-lg rounded-4 border">
        
        <div class="col-md-7 text-center">
            <h1 class="fw-bold mb-4 text-dark"><?= $p['titre'] ?></h1>
            <div class="position-relative">
                <img src="<?= $src_image ?>" class="img-fluid rounded-4 shadow mb-4" alt="<?= $p['titre'] ?>" style="max-height: 400px; width: 100%; object-fit: cover;">
                <span class="badge bg-primary position-absolute top-0 start-0 m-3 px-3 py-2">
                    <i class="bi bi-geo-alt"></i> <?= $p['ville'] ?>
                </span>
            </div>
            <p class="text-muted fs-5 fst-italic">"<?= nl2br($p['description']) ?>"</p>
        </div>

        <div class="col-md-5 ps-lg-5">
            <div class="card border-0 bg-light p-4 rounded-4">
                <h4 class="fw-bold border-bottom pb-3 mb-4"><i class="bi bi-info-circle-fill text-primary"></i> Récapitulatif</h4>
                
                <div class="mb-3">
                    <label class="text-uppercase small fw-bold text-muted">Localisation</label>
                    <p class="mb-0 text-dark"><?= $p['adresse'] ?>, <?= $p['cp'] ?> <?= $p['ville'] ?></p>
                </div>

                <div class="mb-3">
                    <label class="text-uppercase small fw-bold text-muted">Disponibilité</label>
                    <p class="mb-0 text-dark">
                        <i class="bi bi-calendar-range me-2"></i>Du <strong><?= date('d/m/Y H:i', strtotime($p['date_arrivee'])) ?></strong><br>
                        <i class="bi bi-calendar-range-fill me-2"></i>Au <strong><?= date('d/m/Y H:i', strtotime($p['date_depart'])) ?></strong>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="text-uppercase small fw-bold text-muted">Capacité</label>
                    <p class="mb-0 text-dark"><i class="bi bi-people-fill me-2"></i>Jusqu'à <?= $p['capacite'] ?> personnes</p>
                </div>

                <div class="bg-white p-3 rounded-3 shadow-sm border mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Prix HT :</span>
                        <span><?= number_format($prix_ht, 2, ',', ' ') ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">TVA (20%) :</span>
                        <span><?= number_format($tva, 2, ',', ' ') ?> €</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Total TTC :</span>
                        <span class="text-success fw-bold fs-3"><?= number_format($prix_ttc, 2, ',', ' ') ?> €</span>
                    </div>
                </div>

                <?php if(estConnecte()): ?>
                    <form action="panier.php" method="post">
                        <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                        <button type="submit" name="ajout_panier" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm py-3 mb-3">
                            <i class="bi bi-cart-plus-fill me-2"></i> Ajouter au panier
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning border-0 shadow-sm text-center py-3">
                        <p class="mb-2 small">Vous devez être membre pour réserver</p>
                        <a href="connexion.php" class="btn btn-dark btn-sm w-100 mb-2">Se connecter</a>
                        <a href="inscription.php" class="text-decoration-none small">Créer un compte</a>
                    </div>
                <?php endif; ?>
                
                <a href="index.php" class="btn btn-link btn-sm text-muted text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Retour aux offres
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>