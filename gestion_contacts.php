<?php
require_once("../inc/init.inc.php");

// 1. Sécurité Admin
if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 2. Suppression d'un message
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_contact'])) {
    $pdo->query("DELETE FROM contact WHERE id_contact = '$_GET[id_contact]'");
    $content .= "<div class='alert alert-success'>Le message a été supprimé.</div>";
}

// 3. Récupération des messages
$resultat = $pdo->query("SELECT * FROM contact ORDER BY date_enregistrement DESC");

require_once("../inc/header.inc.php");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Contacts</h1>
        <span class="badge bg-info"><?= $resultat->rowCount() ?> message(s) reçu(s)</span>
    </div>

    <?php echo $content; ?>

    <div class="table-responsive shadow-sm card">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Expéditeur</th>
                    <th>Objet</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($message = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $message['id_contact'] ?></td>
                        <td>
                            <strong><?= $message['nom'] ?></strong><br>
                            <small class="text-muted"><?= $message['email'] ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $message['objet'] ?></span></td>
                        <td>
                            <div class="text-wrap" style="max-width: 400px; font-size: 0.9rem;">
                                <?= nl2br($message['message']) ?>
                            </div>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($message['date_enregistrement'])) ?></td>
                        <td>
                            <a href="mailto:<?= $message['email'] ?>?subject=RE: <?= $message['objet'] ?>" class="btn btn-sm btn-outline-primary mb-1">
                                📩 Répondre
                            </a>
                            <a href="?action=suppression&id_contact=<?= $message['id_contact'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer définitivement ce message ?')">
                                🗑️
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>