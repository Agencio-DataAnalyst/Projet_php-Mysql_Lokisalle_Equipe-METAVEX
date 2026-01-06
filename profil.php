<?php
require_once("inc/init.inc.php");
if(!estConnecte()) { header("location:connexion.php"); exit(); }

$id_membre = $_SESSION['membre']['id_membre'];

// 1. Requête pour l'historique : on utilise p.prix car montant n'existe pas dans c (commande)
$resultat = $pdo->prepare("SELECT c.id_commande, c.date_enregistrement, p.prix, s.titre, s.photo, s.id_salle 
                           FROM commande c 
                           INNER JOIN produit p ON c.id_produit = p.id_produit 
                           INNER JOIN salle s ON p.id_salle = s.id_salle 
                           WHERE c.id_membre = ?
                           ORDER BY c.date_enregistrement DESC");
$resultat->execute([$id_membre]);

// 2. Statistiques Admin (corrigées pour éviter les erreurs de colonnes)
$stats = [];
if(estAdmin()) {
    $stats['salles'] = $pdo->query("SELECT COUNT(*) FROM salle")->fetchColumn();
    $stats['commandes'] = $pdo->query("SELECT COUNT(*) FROM commande")->fetchColumn();
    
    // On calcule le CA en joignant la table produit puisque le prix est là-bas
    $query_ca = $pdo->query("SELECT SUM(p.prix) FROM produit p INNER JOIN commande c ON p.id_produit = c.id_produit");
    $stats['ca'] = ($query_ca) ? $query_ca->fetchColumn() : 0;
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-dark text-white p-4 rounded-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-<?= (estAdmin()) ? 'danger' : 'primary' ?> rounded-circle p-3 shadow">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                    </div>
                    <div class="ms-4">
                        <h2 class="mb-0 fw-bold">Bienvenue, <?= $_SESSION['membre']['prenom'] ?> !</h2>
                        <p class="mb-0 text-white-50"><?= $_SESSION['membre']['email'] ?> • 
                            <span class="badge bg-<?= (estAdmin()) ? 'danger' : 'success' ?>">
                                <?= (estAdmin()) ? 'Accès Administrateur' : 'Compte Membre' ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php if(estAdmin()): ?>
                <div class="card border-0 shadow-sm mb-4 rounded-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-speedometer2 me-2"></i>Vue d'ensemble</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 fw-bold mb-0"><?= $stats['salles'] ?></div>
                                    <div class="small text-muted">Salles</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 fw-bold mb-0"><?= $stats['commandes'] ?></div>
                                    <div class="small text-muted">Ventes</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-danger bg-opacity-10 rounded text-center">
                                    <div class="h4 fw-bold text-danger mb-0"><?= number_format($stats['ca'] * 1.2, 2, ',', ' ') ?> €</div>
                                    <div class="small text-danger">Chiffre d'Affaires (TTC)</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="admin/gestion_commandes.php" class="btn btn-dark btn-sm rounded-pill">Gérer les commandes</a>
                            <a href="admin/gestion_salles.php" class="btn btn-outline-danger btn-sm rounded-pill">Gestion catalogue</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Mes informations</h5>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Nom</span>
                            <span class="fw-bold"><?= $_SESSION['membre']['nom'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Prénom</span>
                            <span class="fw-bold"><?= $_SESSION['membre']['prenom'] ?></span>
                        </li>
                    </ul>
                    <a href="connexion.php?action=deconnexion" class="btn btn-outline-secondary w-100 mt-3 btn-sm rounded-pill">Déconnexion</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Historique des réservations</h5>
                    <span class="badge bg-light text-dark border"><?= $resultat->rowCount() ?> total</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Salle</th>
                                <th>Détails</th>
                                <th>Prix TTC</th>
                                <th class="text-end pe-4">Fiche</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($resultat->rowCount() > 0): ?>
                                <?php while($c = $resultat->fetch(PDO::FETCH_ASSOC)) : 
                                     $img = (!empty($c['photo']) && file_exists("photo/".$c['photo'])) ? "photo/".$c['photo'] : "photo/default.jpg";
                                ?>
                                <tr>
                                    <td class="ps-4" style="width: 100px;">
                                        <img src="<?= $img ?>" class="rounded-3 shadow-sm img-fluid" style="height: 60px; width: 80px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= $c['titre'] ?></div>
                                        <div class="text-muted small">
                                            Réservé le <?= date('d/m/Y', strtotime($c['date_enregistrement'])) ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-primary"><?= number_format($c['prix'] * 1.2, 2, ',', ' ') ?> €</td>
                                    <td class="text-end pe-4">
                                        <a href="fiche_produit.php?id_produit=<?= $c['id_salle'] ?>" class="btn btn-sm btn-outline-primary rounded-circle">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted mb-0">Aucune commande trouvée.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>