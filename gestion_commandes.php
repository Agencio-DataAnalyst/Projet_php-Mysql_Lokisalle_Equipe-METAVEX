<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 0. Initialisation des variables
$content = "";
$total_ca = 0;

// 1. Suppression d'une commande
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_commande'])) {
    $prepare = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id");
    $prepare->bindValue(':id', $_GET['id_commande'], PDO::PARAM_INT);
    $prepare->execute();
    
    $content .= "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <i class='bi bi-check-circle me-2'></i>La commande #<b>" . htmlspecialchars($_GET['id_commande']) . "</b> a été supprimée.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                 </div>";
}

// 2. Récupération des commandes avec les infos liées
$requete = "SELECT c.id_commande, c.date_enregistrement, m.email, m.pseudo, s.titre, p.date_arrivee, p.date_depart, p.prix 
            FROM commande c
            INNER JOIN membre m ON c.id_membre = m.id_membre
            INNER JOIN produit p ON c.id_produit = p.id_produit
            INNER JOIN salle s ON p.id_salle = s.id_salle
            ORDER BY c.date_enregistrement DESC";
$resultat = $pdo->query($requete);

require_once("../inc/header.inc.php");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0"><i class="bi bi-cart-check text-success"></i> Gestion des Commandes</h1>
            <p class="text-muted">Suivi des ventes et chiffre d'affaires de Lokisalle</p>
        </div>
        <div class="text-end">
            <div class="p-3 bg-white shadow-sm rounded-3 border">
                <small class="text-muted text-uppercase fw-bold d-block">Ventes totales</small>
                <span class="h4 fw-bold mb-0 text-primary"><?= $resultat->rowCount() ?></span>
            </div>
        </div>
    </div>

    <?= $content ?>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Client</th>
                        <th>Salle</th>
                        <th>Période de location</th>
                        <th>Prix HT</th>
                        <th>Total TTC</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if($resultat->rowCount() > 0): ?>
                        <?php while($commande = $resultat->fetch(PDO::FETCH_ASSOC)) : 
                            $ttc = $commande['prix'] * 1.2;
                            $total_ca += $ttc;
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= $commande['id_commande'] ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($commande['pseudo']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($commande['email']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-2">
                                    <?= htmlspecialchars($commande['titre']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="small lh-sm">
                                    <i class="bi bi-calendar-event text-muted me-1"></i> Du <?= date('d/m/Y', strtotime($commande['date_arrivee'])) ?><br>
                                    <i class="bi bi-calendar-check text-muted me-1"></i> Au <?= date('d/m/Y', strtotime($commande['date_depart'])) ?>
                                </div>
                            </td>
                            <td class="text-muted"><?= number_format($commande['prix'], 2, ',', ' ') ?> €</td>
                            <td class="fw-bold text-success fs-5"><?= number_format($ttc, 2, ',', ' ') ?> €</td>
                            <td class="text-center pe-4">
                                <a href="?action=supprimer&id_commande=<?= $commande['id_commande'] ?>" 
                                   class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                   onclick="return confirm('Attention ! Supprimer cette commande annulera la vente définitivement. Confirmer ?')">
                                    <i class="bi bi-trash me-1"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Aucune commande n'a encore été passée sur le site.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light border-top border-2">
                    <tr>
                        <td colspan="5" class="text-end py-4 ps-4 fw-bold text-uppercase align-middle">
                            <span class="fs-4">Chiffre d'affaires Global :</span>
                        </td>
                        <td colspan="2" class="py-4 pe-4 align-middle">
                            <span class="badge bg-success fs-3 px-4 py-2 shadow-sm">
                                <?= number_format($total_ca, 2, ',', ' ') ?> € TTC
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>