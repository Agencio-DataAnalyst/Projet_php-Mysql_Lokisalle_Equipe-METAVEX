<?php
require_once("inc/init.inc.php");

// 1. On vérifie qu'on a bien un id_produit dans l'URL
if(!isset($_GET['id_produit'])) {
    header("location:index.php");
    exit();
}

// 2. Récupération des détails (Jointure Salle + Produit)
$resultat = $pdo->prepare("SELECT p.*, s.* FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.id_produit = :id_produit");
$resultat->execute([':id_produit' => $_GET['id_produit']]);

if($resultat->rowCount() <= 0) { header("location:index.php"); exit(); }

$fiche = $resultat->fetch(PDO::FETCH_ASSOC);

// 3. Traitement de la réservation (quand on clique sur le bouton)
if(isset($_POST['reserver'])) {
    if(estConnecte()) {
        // Insertion dans la table commande
        $pdo->query("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (" . $_SESSION['membre']['id_membre'] . ", " . $_GET['id_produit'] . ", NOW())");
        
        // Mise à jour de l'état du produit
        $pdo->query("UPDATE produit SET etat = 'reservation' WHERE id_produit = " . $_GET['id_produit']);
        
        header("location:profil.php?statut=confirmation");
        exit();
    } else {
        $content .= "<div class='alert alert-warning'>Veuillez vous <a href='connexion.php'>connecter</a> pour réserver cette salle.</div>";
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <?php echo $content; ?>
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-3"><?= $fiche['titre'] ?></h1>
            <img src="photo/<?= $fiche['photo'] ?>" class="img-fluid rounded shadow" alt="<?= $fiche['titre'] ?>">
            <hr>
            <h3>Description</h3>
            <p><?= $fiche['description'] ?></p>
        </div>

        <div class="col-md-4">
            <div class="card bg-light p-4 shadow-sm">
                <h4 class="text-center">Informations</h4>
                <ul class="list-unstyled mt-3">
                    <li><strong>Ville :</strong> <?= $fiche['ville'] ?> (<?= $fiche['cp'] ?>)</li>
                    <li><strong>Adresse :</strong> <?= $fiche['adresse'] ?></li>
                    <li><strong>Capacité :</strong> <?= $fiche['capacite'] ?> personnes</li>
                    <li><strong>Catégorie :</strong> <?= $fiche['categorie'] ?></li>
                </ul>
                <hr>
                <div class="text-center">
                    <p class="mb-0">Du <?= date('d/m/Y H:i', strtotime($fiche['date_arrivee'])) ?></p>
                    <p>au <?= date('d/m/Y H:i', strtotime($fiche['date_depart'])) ?></p>
                    <h2 class="text-primary"><?= number_format($fiche['prix'] * 1.2, 2) ?> € TTC</h2>
                </div>

                <form method="post" class="mt-4">
                    <?php if(estConnecte()): ?>
                        <button type="submit" name="reserver" class="btn btn-success btn-lg w-100">Réserver maintenant</button>
                    <?php else: ?>
                        <a href="inscription.php" class="btn btn-primary w-100">Créer un compte pour réserver</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>