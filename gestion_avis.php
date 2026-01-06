<?php
require_once("../inc/init.inc.php");

// 1. Sécurité Admin
if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 2. Suppression d'un avis
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_avis'])) {
    $pdo->query("DELETE FROM avis WHERE id_avis = '$_GET[id_avis]'");
    $content .= "<div class='alert alert-success'>L'avis a bien été supprimé.</div>";
}

// 3. Récupération des avis avec Jointures
$requete = "SELECT a.*, m.pseudo, m.email, s.titre 
            FROM avis a
            INNER JOIN membre m ON a.id_membre = m.id_membre
            INNER JOIN salle s ON a.id_salle = s.id_salle
            ORDER BY a.date_enregistrement DESC";
$resultat = $pdo->query($requete);

require_once("../inc/header.inc.php");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Avis</h1>
        <span class="badge bg-warning text-dark"><?= $resultat->rowCount() ?> avis clients</span>
    </div>

    <?php echo $content; ?>

    <div class="table-responsive shadow-sm card">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Membre</th>
                    <th>Salle</th>
                    <th>Commentaire</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($avis = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $avis['id_avis'] ?></td>
                        <td>
                            <strong><?= $avis['pseudo'] ?></strong><br>
                            <small class="text-muted"><?= $avis['email'] ?></small>
                        </td>
                        <td><?= $avis['titre'] ?></td>
                        <td>
                            <div class="small text-wrap" style="max-width: 300px;">
                                "<?= $avis['commentaire'] ?>"
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= $avis['note'] ?> / 10</span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($avis['date_enregistrement'])) ?></td>
                        <td>
                            <a href="?action=suppression&id_avis=<?= $avis['id_avis'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cet avis ?')">
                                🗑️ Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>