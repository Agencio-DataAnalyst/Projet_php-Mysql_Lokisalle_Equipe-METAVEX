<?php
require_once("inc/init.inc.php");
if(!estConnecte()) { header("location:connexion.php"); exit(); }

$id_commande = $_GET['id'];
$id_membre = $_SESSION['membre']['id_membre'];

// Requête complète pour récupérer toutes les infos de la salle et du produit
$resultat = $pdo->query("SELECT c.*, p.*, s.* FROM commande c 
                        INNER JOIN produit p ON c.id_produit = p.id_produit 
                        INNER JOIN salle s ON p.id_salle = s.id_salle 
                        WHERE c.id_commande = '$id_commande' AND c.id_membre = '$id_membre'");

$commande = $resultat->fetch(PDO::FETCH_ASSOC);

if(!$commande) { header("location:profil.php"); exit(); }

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="profil.php">Mon Profil</a></li>
        <li class="breadcrumb-item active">Commande #<?= $commande['id_commande'] ?></li>
      </ol>
    </nav>

    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="photo/<?= $commande['photo'] ?>" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="<?= $commande['titre'] ?>">
            </div>
            <div class="col-md-6 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Détails de réservation</h2>
                    <span class="badge bg-success">Payée</span>
                </div>
                
                <h4 class="text-primary mb-4"><?= $commande['titre'] ?></h4>
                
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Adresse :</span> <strong><?= $commande['adresse'] ?>, <?= $commande['ville'] ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date d'arrivée :</span> <strong><?= date('d/m/Y H:i', strtotime($commande['date_arrivee'])) ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date de départ :</span> <strong><?= date('d/m/Y H:i', strtotime($commande['date_depart'])) ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Capacité :</span> <strong><?= $commande['capacite'] ?> personnes</strong>
                    </li>
                </ul>

                <div class="bg-light p-3 rounded text-center">
                    <span class="h5 text-muted">Montant total réglé</span>
                    <h2 class="text-dark fw-bold mb-0"><?= number_format($commande['prix'] * 1.2, 2) ?> € TTC</h2>
                </div>

                <div class="mt-4">
                    <button onclick="window.print()" class="btn btn-outline-dark w-100"><i class="bi bi-printer"></i> Imprimer la confirmation</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>