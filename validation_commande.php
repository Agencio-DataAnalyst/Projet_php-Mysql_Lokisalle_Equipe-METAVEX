<?php
require_once("inc/init.inc.php");

// 1. Sécurité : on doit être connecté et avoir un panier
if (!estConnecte() || !isset($_SESSION['panier']) || empty($_SESSION['panier']['id_produit'])) {
    header("location:index.php");
    exit();
}

$erreur = "";

// 2. BOUCLE SUR LE PANIER POUR VÉRIFIER LA DISPONIBILITÉ
for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
    $id_p = $_SESSION['panier']['id_produit'][$i];
    
    $res = $pdo->query("SELECT etat FROM produit WHERE id_produit = $id_p");
    $produit = $res->fetch(PDO::FETCH_ASSOC);

    if ($produit['etat'] != 'libre') {
        $erreur .= "Désolé, la salle '" . $_SESSION['panier']['titre'][$i] . "' vient d'être réservée par un autre utilisateur.<br>";
    }
}

// 3. SI PAS D'ERREUR, ON VALIDE LA COMMANDE
if (empty($erreur)) {
    // A. Calcul du montant total
    $montant_total = 0;
    foreach ($_SESSION['panier']['prix'] as $prix) {
        $montant_total += $prix * 1.2; // On enregistre le montant TTC
    }

    // B. Insertion dans la table COMMANDE
    $id_membre = $_SESSION['membre']['id_membre'];
    $pdo->query("INSERT INTO commande (id_membre, montant, date_enregistrement) 
                 VALUES ($id_membre, $montant_total, NOW())");
    $id_commande = $pdo->lastInsertId();

    // C. Mise à jour des produits et lien avec la commande
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        $id_p = $_SESSION['panier']['id_produit'][$i];
        
        // On marque le produit comme réservé
        $pdo->query("UPDATE produit SET etat = 'reserve' WHERE id_produit = $id_p");
        
        // Note : Si votre table 'commande' contient une colonne id_produit (cas d'une commande par produit), 
        // ou si vous avez une table de liaison, c'est ici qu'on l'insère.
    }

    // D. On vide le panier
    unset($_SESSION['panier']);
    $success = "Félicitations ! Votre réservation n°$id_commande a bien été enregistrée. Vous allez recevoir un mail de confirmation.";
}

require_once("inc/header.inc.php");
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php if (!empty($erreur)) : ?>
                <div class="card shadow border-0 p-5 rounded-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger display-1 mb-4"></i>
                    <h2 class="fw-bold text-danger">Erreur de disponibilité</h2>
                    <p class="lead mt-3"><?= $erreur ?></p>
                    <div class="mt-4">
                        <a href="panier.php" class="btn btn-outline-dark btn-lg px-5">Retour au panier</a>
                    </div>
                </div>
            <?php else : ?>
                <div class="card shadow border-0 p-5 rounded-4 bg-white">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="fw-bold text-dark">Paiement Accepté !</h1>
                    <p class="lead text-muted mt-3"><?= $success ?></p>
                    
                    <div class="bg-light p-4 rounded-3 my-4 border">
                        <p class="mb-1">Numéro de commande : <strong>#<?= $id_commande ?></strong></p>
                        <p class="mb-0">Un récapitulatif a été envoyé à : <strong><?= $_SESSION['membre']['email'] ?></strong></p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="profil.php" class="btn btn-primary btn-lg px-4 shadow-sm">Consulter mes commandes</a>
                        <a href="index.php" class="btn btn-outline-secondary btn-lg px-4">Retour à l'accueil</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>