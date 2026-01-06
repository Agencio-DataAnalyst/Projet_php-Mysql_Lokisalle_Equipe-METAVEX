<?php
require_once("../inc/init.inc.php");

// 1. Sécurité
if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 2. Suppression d'un membre
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_membre'])) {
    $pdo->query("DELETE FROM membre WHERE id_membre = '$_GET[id_membre]'");
    $content .= "<div class='alert alert-success'>Le membre a bien été supprimé.</div>";
}

// 3. Changement de statut (Admin <-> Membre)
if(isset($_GET['action']) && $_GET['action'] == 'statut' && isset($_GET['id_membre'])) {
    $nouveau_statut = ($_GET['statut'] == 0) ? 1 : 0;
    $pdo->query("UPDATE membre SET statut = '$nouveau_statut' WHERE id_membre = '$_GET[id_membre]'");
    $content .= "<div class='alert alert-info'>Le statut du membre a été mis à jour.</div>";
}

// 4. Récupération des membres
$resultat = $pdo->query("SELECT * FROM membre");

require_once("../inc/header.inc.php");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Membres</h1>
        <span class="badge bg-primary"><?= $resultat->rowCount() ?> inscrits</span>
    </div>

    <?php echo $content = ''; ?>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>
                    <th>Nom / Prénom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($membre = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $membre['id_membre'] ?></td>
                        <td><strong><?= $membre['pseudo'] ?></strong></td>
                        <td><?= strtoupper($membre['nom']) ?> <?= $membre['prenom'] ?></td>
                        <td><?= $membre['email'] ?></td>
                        <td>
                            <?php if($membre['statut'] == 1): ?>
                                <span class="badge bg-danger">Administrateur</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Membre</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?action=statut&id_membre=<?= $membre['id_membre'] ?>&statut=<?= $membre['statut'] ?>" class="btn btn-sm btn-outline-warning" title="Changer le statut">
                                🔄
                            </a>
                            <?php if($membre['id_membre'] != $_SESSION['membre']['id_membre']): // Empêcher de se supprimer soi-même ?>
                                <a href="?action=suppression&id_membre=<?= $membre['id_membre'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                    🗑️
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>