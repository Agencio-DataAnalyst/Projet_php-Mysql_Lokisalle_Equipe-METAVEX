<?php
require_once("inc/init.inc.php");

// 1. FILTRES DYNAMIQUES
$conditions = " WHERE p.etat = 'libre' AND p.date_arrivee >= NOW() ";
$params = [];

if (!empty($_GET['categorie'])) {
    $conditions .= " AND s.categorie = :categorie ";
    $params[':categorie'] = $_GET['categorie'];
}

if (!empty($_GET['ville'])) {
    $conditions .= " AND s.ville = :ville ";
    $params[':ville'] = $_GET['ville'];
}

if (!empty($_GET['capacite'])) {
    $conditions .= " AND s.capacite >= :capacite ";
    $params[':capacite'] = $_GET['capacite'];
}

// 2. REQUÊTE PRINCIPALE
$requete = $pdo->prepare("SELECT p.*, s.titre, s.photo, s.ville, s.capacite, s.categorie 
                          FROM produit p 
                          INNER JOIN salle s ON p.id_salle = s.id_salle 
                          $conditions 
                          ORDER BY p.date_arrivee ASC");
$requete->execute($params);

// 3. RÉCUPÉRATION DES OPTIONS POUR LES FILTRES (menus déroulants)
$villes = $pdo->query("SELECT DISTINCT ville FROM salle ORDER BY ville ASC")->fetchAll(PDO::FETCH_COLUMN);
$categories = $pdo->query("SELECT DISTINCT categorie FROM salle ORDER BY categorie ASC")->fetchAll(PDO::FETCH_COLUMN);

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h4 class="fw-bold mb-4"><i class="bi bi-filter-left text-primary"></i> Filtrer</h4>
                
                <form method="get">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Catégorie</label>
                        <select name="categorie" class="form-select border-0 bg-light">
                            <option value="">Toutes</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat ?>" <?= (isset($_GET['categorie']) && $_GET['categorie'] == $cat) ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Ville</label>
                        <select name="ville" class="form-select border-0 bg-light">
                            <option value="">Toutes</option>
                            <?php foreach($villes as $v): ?>
                                <option value="<?= $v ?>" <?= (isset($_GET['ville']) && $_GET['ville'] == $v) ? 'selected' : '' ?>><?= ucfirst($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Capacité Minimum</label>
                        <input type="number" name="capacite" class="form-control border-0 bg-light" value="<?= $_GET['capacite'] ?? '' ?>" placeholder="Ex: 10">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm mb-2">Rechercher</button>
                    <a href="recherche.php" class="btn btn-link w-100 text-muted small">Réinitialiser</a>
                </form>
            </div>
        </div>

        <div class="col-lg-9">
            <h2 class="fw-bold mb-4"><?= $requete->rowCount() ?> offre(s) disponible(s)</h2>
            
            <div class="row g-4">
                <?php if($requete->rowCount() > 0): ?>
                    <?php while($p = $requete->fetch(PDO::FETCH_ASSOC)): 
                        $img = (!empty($p['photo']) && file_exists("photo/".$p['photo'])) ? "photo/".$p['photo'] : "photo/default.jpg";
                    ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-offer" style="transition: transform 0.3s;">
                                <div class="position-relative">
                                    <img src="<?= $img ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                                    <div class="badge bg-dark position-absolute top-0 end-0 m-2 shadow">
                                        <?= number_format($p['prix'], 0, ',', ' ') ?> € HT
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="fw-bold mb-1 text-truncate"><?= htmlspecialchars($p['titre']) ?></h5>
                                    <p class="text-muted small mb-3"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($p['ville']) ?> • <?= $p['capacite'] ?> pers.</p>
                                    
                                    <div class="bg-light p-2 rounded small mb-3">
                                        <i class="bi bi-calendar-event me-2"></i>Du <?= date('d/m/Y', strtotime($p['date_arrivee'])) ?><br>
                                        <i class="bi bi-calendar-x me-2"></i>Au <?= date('d/m/Y', strtotime($p['date_depart'])) ?>
                                    </div>

                                    <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-outline-primary w-100 rounded-pill">Voir l'offre</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">Aucun résultat ne correspond à vos critères.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .card-offer:hover { transform: translateY(-5px); }
</style>

<?php require_once("inc/footer.inc.php"); ?>