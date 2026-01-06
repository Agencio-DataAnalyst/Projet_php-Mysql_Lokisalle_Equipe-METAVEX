<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

$content = "";

// 2. SUPPRESSION
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_produit'])) {
    $prepare = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id");
    $prepare->bindValue(':id', $_GET['id_produit'], PDO::PARAM_INT);
    $prepare->execute();
    header("location:gestion_produits.php?msg=deleted");
    exit();
}

// 3. ENREGISTREMENT
if($_POST) {
    $date_arrivee = str_replace('T', ' ', $_POST['date_arrivee']);
    $date_depart = str_replace('T', ' ', $_POST['date_depart']);

    // Vérification de collision de dates pour la même salle
    $verif = $pdo->prepare("SELECT * FROM produit WHERE id_salle = :id_salle AND (
        (:arr BETWEEN date_arrivee AND date_depart) OR 
        (:dep BETWEEN date_arrivee AND date_depart) OR
        (date_arrivee BETWEEN :arr2 AND :dep2)
    )");
    $verif->execute([
        ':id_salle' => $_POST['id_salle'],
        ':arr' => $date_arrivee, ':dep' => $date_depart,
        ':arr2' => $date_arrivee, ':dep2' => $date_depart
    ]);

    if($_POST['date_arrivee'] >= $_POST['date_depart']) {
        $content .= "<div class='alert alert-danger shadow-sm text-center'>Erreur : La date de départ doit être après l'arrivée.</div>";
    } elseif($verif->rowCount() > 0) {
        $content .= "<div class='alert alert-danger shadow-sm text-center'>Attention : La salle est déjà programmée sur tout ou partie de ce créneau.</div>";
    } else {
        $insertion = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, 'libre')");
        $insertion->execute([
            ':id_salle'     => $_POST['id_salle'],
            ':date_arrivee' => $date_arrivee,
            ':date_depart'  => $date_depart,
            ':prix'         => $_POST['prix']
        ]);
        header("location:gestion_produits.php?msg=success");
        exit();
    }
}

if(isset($_GET['msg'])) {
    if($_GET['msg'] == 'success') $content .= "<div class='alert alert-success shadow-sm text-center'>L'offre a été publiée !</div>";
    if($_GET['msg'] == 'deleted') $content .= "<div class='alert alert-warning shadow-sm text-center'>Offre supprimée.</div>";
}

// 4. RÉCUPÉRATION
$resultat_produits = $pdo->query("SELECT p.*, s.titre, s.ville FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle ORDER BY p.date_arrivee ASC");
$liste_salles = $pdo->query("SELECT id_salle, titre, ville FROM salle ORDER BY titre ASC");

require_once("../inc/header.inc.php");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="bi bi-calendar-plus text-primary"></i> Gestion des Offres</h1>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="collapse" data-bs-target="#formulaireProduit">
            <i class="bi bi-plus-circle me-2"></i>Nouveau créneau
        </button>
    </div>

    <?= $content ?>

    <div class="collapse mb-5" id="formulaireProduit">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0">Publier une nouvelle période de location</h5>
            </div>
            <div class="card-body p-4 bg-light">
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase">Choisir la Salle</label>
                            <select name="id_salle" class="form-select border-0 shadow-sm" required>
                                <?php while($salle = $liste_salles->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?= $salle['id_salle'] ?>">
                                        <?= htmlspecialchars($salle['titre']) ?> - <?= htmlspecialchars($salle['ville']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase">Arrivée</label>
                            <input type="datetime-local" name="date_arrivee" class="form-control border-0 shadow-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase">Départ</label>
                            <input type="datetime-local" name="date_depart" class="form-control border-0 shadow-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase">Prix HT (€)</label>
                            <input type="number" name="prix" class="form-control border-0 shadow-sm" placeholder="Ex: 500" required>
                        </div>
                        <div class="col-md-8 text-end d-flex align-items-end justify-content-end">
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow">Mettre en ligne</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Détails Salle</th>
                        <th>Dates de Location</th>
                        <th>Tarifs</th>
                        <th>Statut</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php while($prod = $resultat_produits->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr class="<?= ($prod['etat'] == 'reservation') ? 'table-light text-muted' : '' ?>">
                        <td class="ps-4">#<?= $prod['id_produit'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($prod['titre']) ?></div>
                            <small class="badge bg-info text-dark"><?= htmlspecialchars($prod['ville']) ?></small>
                        </td>
                        <td>
                            <div class="small">
                                <i class="bi bi-calendar-check text-success"></i> <?= date('d/m/Y H:i', strtotime($prod['date_arrivee'])) ?><br>
                                <i class="bi bi-calendar-x text-danger"></i> <?= date('d/m/Y H:i', strtotime($prod['date_depart'])) ?>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold"><?= number_format($prod['prix'], 0, ',', ' ') ?> € HT</div>
                            <div class="small text-muted"><?= number_format($prod['prix']*1.2, 0, ',', ' ') ?> € TTC</div>
                        </td>
                        <td>
                            <?php if($prod['etat'] == 'libre'): ?>
                                <span class="badge bg-success-subtle text-success border border-success px-3">Libre</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3">Occupé</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center pe-4">
                            <a href="?action=supprimer&id_produit=<?= $prod['id_produit'] ?>" 
                               class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" 
                               onclick="return confirm('Supprimer ce créneau ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>