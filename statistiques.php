<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

require_once("../inc/header.inc.php");

// 1. Les 5 salles les mieux notées
$top_notes = $pdo->query("SELECT s.titre, AVG(a.note) as moyenne 
                          FROM salle s 
                          JOIN avis a ON s.id_salle = a.id_salle 
                          GROUP BY s.id_salle 
                          ORDER BY moyenne DESC LIMIT 5");

// 2. Les 5 salles les plus louées
$top_louees = $pdo->query("SELECT s.titre, COUNT(c.id_commande) as nb_resas 
                            FROM salle s 
                            JOIN produit p ON s.id_salle = p.id_salle 
                            JOIN commande c ON p.id_produit = c.id_produit 
                            GROUP BY s.id_salle 
                            ORDER BY nb_resas DESC LIMIT 5");

// 3. Calcul du Chiffre d'Affaires Total (somme des prix des produits commandés)
$ca_total = $pdo->query("SELECT SUM(p.prix) FROM produit p JOIN commande c ON p.id_produit = c.id_produit")->fetchColumn();
?>

<style>
    /* Effet de zoom au survol des cartes pour un aspect interactif */
    .stat-card { transition: transform 0.3s, box-shadow 0.3s; text-decoration: none !important; display: block; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important; }
</style>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold"><i class="bi bi-speedometer2 text-primary"></i> Tableau de Bord</h1>
            <p class="text-muted">Cliquez sur une carte pour gérer la section correspondante.</p>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <a href="gestion_salles.php" class="card bg-primary text-white border-0 shadow-sm stat-card h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-building fs-1"></i>
                    <h6 class="text-uppercase small mt-2">Salles</h6>
                    <p class="display-6 fw-bold mb-0"><?= $pdo->query("SELECT COUNT(*) FROM salle")->fetchColumn(); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="gestion_commandes.php" class="card bg-success text-white border-0 shadow-sm stat-card h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-cart-check fs-1"></i>
                    <h6 class="text-uppercase small mt-2">Ventes</h6>
                    <p class="display-6 fw-bold mb-0"><?= $pdo->query("SELECT COUNT(*) FROM commande")->fetchColumn(); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="gestion_membres.php" class="card bg-info text-white border-0 shadow-sm stat-card h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people fs-1"></i>
                    <h6 class="text-uppercase small mt-2">Membres</h6>
                    <p class="display-6 fw-bold mb-0"><?= $pdo->query("SELECT COUNT(*) FROM membre")->fetchColumn(); ?></p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="gestion_commandes.php" class="card bg-dark text-white border-0 shadow-sm stat-card h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-currency-euro fs-1"></i>
                    <h6 class="text-uppercase small mt-2">C.A. Global</h6>
                    <p class="display-6 fw-bold mb-0"><?= number_format($ca_total ?? 0, 0, '.', ' ') ?> €</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header fw-bold bg-white border-0 py-3">
                    <i class="bi bi-star-fill text-warning me-2"></i> Top 5 des mieux notées
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Salle</th><th class="text-end pe-4">Note</th></tr></thead>
                        <tbody>
                            <?php if($top_notes->rowCount() > 0): ?>
                                <?php while($row = $top_notes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td class="ps-4"><?= htmlspecialchars($row['titre']) ?></td>
                                        <td class="text-end pe-4"><span class="badge bg-warning text-dark"><?= round($row['moyenne'], 1) ?> / 10</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center text-muted py-4">Aucun avis enregistré.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header fw-bold bg-white border-0 py-3">
                    <i class="bi bi-graph-up text-success me-2"></i> Top 5 des plus louées
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Salle</th><th class="text-end pe-4">Réservations</th></tr></thead>
                        <tbody>
                            <?php if($top_louees->rowCount() > 0): ?>
                                <?php while($row = $top_louees->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td class="ps-4"><?= htmlspecialchars($row['titre']) ?></td>
                                        <td class="text-end pe-4"><span class="badge bg-success"><?= $row['nb_resas'] ?> résas</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center text-muted py-4">Aucune commande passée.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>