<?php
require_once("inc/init.inc.php");

// Redirection si non connecté
if (!estConnecte()) {
    header("location:connexion.php");
    exit();
}

// Récupération des commandes du membre AVEC les détails de la salle (Jointure)
$id_membre = $_SESSION['membre']['id_membre'];

// On lie Commande -> Produit -> Salle pour avoir toutes les infos
$resultat = $pdo->query("SELECT c.id_commande, c.date_enregistrement, p.prix, s.titre, s.ville 
                         FROM commande c
                         INNER JOIN produit p ON c.id_produit = p.id_produit
                         INNER JOIN salle s ON p.id_salle = s.id_salle
                         WHERE c.id_membre = $id_membre 
                         ORDER BY c.date_enregistrement DESC");

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4 mb-4 text-center rounded-4 bg-white">
                <div class="mb-3">
                    <i class="bi bi-person-circle display-1 text-primary"></i>
                </div>
                <h4 class="fw-bold"><?= ucfirst($_SESSION['membre']['pseudo']) ?></h4>
                <p class="badge bg-primary rounded-pill"><?= ($_SESSION['membre']['statut'] == 1) ? 'Administrateur' : 'Membre Lokisalle' ?></p>
                <hr>
                <div class="text-start">
                    <p class="small mb-1 text-muted">Coordonnées</p>
                    <p class="mb-2"><strong><i class="bi bi-envelope me-2"></i></strong> <?= $_SESSION['membre']['email'] ?></p>
                    <p class="mb-2"><strong><i class="bi bi-person me-2"></i></strong> <?= $_SESSION['membre']['nom'] ?> <?= $_SESSION['membre']['prenom'] ?></p>
                </div>
                <a href="connexion.php?action=deconnexion" class="btn btn-outline-danger w-100 mt-3 rounded-pill">Déconnexion</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                <h4 class="fw-bold mb-4"><i class="bi bi-bag-check text-success me-2"></i> Mes réservations</h4>
                
                <?php if ($resultat->rowCount() > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Salle / Ville</th>
                                    <th>Date d'achat</th>
                                    <th>Montant TTC</th>
                                    <th class="text-center">Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($commande = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $commande['id_commande'] ?></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($commande['titre']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($commande['ville']) ?></div>
                                        </td>
                                        <td class="small"><?= date('d/m/Y à H:i', strtotime($commande['date_enregistrement'])) ?></td>
                                        <td class="text-success fw-bold"><?= number_format($commande['prix'] * 1.2, 2, ',', ' ') ?> €</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-light border rounded-circle"><i class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <p class="text-muted mt-3">Vous n'avez pas encore effectué de réservation.</p>
                        <a href="recherche.php" class="btn btn-primary rounded-pill px-4">Parcourir nos salles</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>