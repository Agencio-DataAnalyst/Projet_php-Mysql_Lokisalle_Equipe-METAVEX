<?php
require_once("inc/init.inc.php");

// 1. Sécurité : seul un membre connecté peut réserver
if(!estConnecte()) {
    header("location:connexion.php");
    exit();
}

// 2. Vérification de l'ID produit
if(!isset($_POST['id_produit'])) {
    header("location:index.php");
    exit();
}

$id_produit = $_POST['id_produit'];
$id_membre = $_SESSION['membre']['id_membre'];

// 3. Vérification de disponibilité (Sécurité anti-doublon)
$verif = $pdo->query("SELECT etat FROM produit WHERE id_produit = '$id_produit'");
$produit = $verif->fetch(PDO::FETCH_ASSOC);

if($produit['etat'] == 'libre') {
    // ÉTAPE A : On passe le produit en 'reserve' pour qu'il disparaisse de l'index
    $pdo->query("UPDATE produit SET etat = 'reserve' WHERE id_produit = '$id_produit'");

    // ÉTAPE B : On enregistre la commande
    $pdo->query("INSERT INTO commande (id_membre, id_produit, date_enregistrement) 
                 VALUES ('$id_membre', '$id_produit', NOW())");
    
    $success = true;
} else {
    $success = false;
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php if($success) : ?>
                <div class="card shadow border-0 p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold">Réservation confirmée !</h2>
                    <p class="lead text-muted">Votre commande a bien été enregistrée. Vous pouvez retrouver tous les détails dans votre espace personnel.</p>
                    <div class="mt-4">
                        <a href="profil.php" class="btn btn-primary btn-lg px-5">Voir mes réservations</a>
                        <a href="index.php" class="btn btn-outline-secondary btn-lg ms-2">Retour à l'accueil</a>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-danger p-5 shadow">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                    <h2 class="mt-3">Désolé, cette salle vient d'être réservée !</h2>
                    <p>Un autre utilisateur a été plus rapide que vous. Veuillez choisir un autre créneau.</p>
                    <a href="index.php" class="btn btn-danger mt-3">Retour aux offres</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>