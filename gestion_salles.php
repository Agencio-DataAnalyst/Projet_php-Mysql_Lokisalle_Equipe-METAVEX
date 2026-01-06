<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

$content = "";
$action = $_GET['action'] ?? '';

// 1. SUPPRESSION
if($action == 'supprimer' && isset($_GET['id_salle'])) {
    $prepare = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id");
    $prepare->bindValue(':id', $_GET['id_salle'], PDO::PARAM_INT);
    $prepare->execute();
    header("location:gestion_salles.php?msg=deleted");
    exit();
}

// 2. ENREGISTREMENT OU MODIFICATION
if($_POST) {
    $photo_bdd = $_POST['photo_actuelle'] ?? "";

    // GESTION DE L'UPLOAD PHOTO
    if(!empty($_FILES['photo']['name'])) {
        // On crée un nom unique pour éviter les doublons
        $nom_photo = $_POST['titre'] . '_' . time() . '_' . $_FILES['photo']['name'];
        $photo_bdd = $nom_photo;
        
        // Chemin physique sur le serveur (plus robuste que DOCUMENT_ROOT)
        $dossier_photo = "../photo/";
        
        // Créer le dossier s'il n'existe pas
        if(!file_exists($dossier_photo)) {
            mkdir($dossier_photo, 0777, true);
        }

        $chemin_final = $dossier_photo . $nom_photo;
        copy($_FILES['photo']['tmp_name'], $chemin_final);
    }

    if(isset($_GET['action']) && $_GET['action'] == 'modifier') {
        $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");
        $enregistrement->bindValue(':id_salle', $_GET['id_salle'], PDO::PARAM_INT);
    } else {
        $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
    }

    $enregistrement->execute([
        ':titre'       => $_POST['titre'],
        ':description' => $_POST['description'],
        ':photo'       => $photo_bdd,
        ':pays'        => $_POST['pays'],
        ':ville'       => $_POST['ville'],
        ':adresse'     => $_POST['adresse'],
        ':cp'          => $_POST['cp'],
        ':capacite'    => $_POST['capacite'],
        ':categorie'   => $_POST['categorie']
    ]);

    header("location:gestion_salles.php?msg=success");
    exit();
}

// Messages d'alerte
if(isset($_GET['msg'])) {
    if($_GET['msg'] == 'success') $content .= "<div class='alert alert-success shadow-sm'>La salle a été enregistrée avec succès !</div>";
    if($_GET['msg'] == 'deleted') $content .= "<div class='alert alert-warning shadow-sm'>La salle a bien été supprimée.</div>";
}

// 3. RÉCUPÉRATION POUR MODIFICATION
if($action == 'modifier' && isset($_GET['id_salle'])) {
    $res = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id");
    $res->bindValue(':id', $_GET['id_salle'], PDO::PARAM_INT);
    $res->execute();
    $salle_actuelle = $res->fetch(PDO::FETCH_ASSOC);
}

$resultat = $pdo->query("SELECT * FROM salle ORDER BY id_salle DESC");
require_once("../inc/header.inc.php");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="bi bi-building-gear text-primary"></i> Gestion des Salles</h1>
        <a href="gestion_salles.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Actualiser l'ajout
        </a>
    </div>

    <?= $content ?>

    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0"><?= (isset($salle_actuelle)) ? "Modifier : " . htmlspecialchars($salle_actuelle['titre']) : "Nouvelle Salle" ?></h5>
        </div>
        <div class="card-body p-4">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-uppercase">Titre</label>
                        <input type="text" name="titre" class="form-control" value="<?= $salle_actuelle['titre'] ?? '' ?>" required placeholder="Nom de la salle">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-uppercase">Catégorie</label>
                        <select name="categorie" class="form-select">
                            <option value="reunion" <?= (isset($salle_actuelle) && $salle_actuelle['categorie'] == 'reunion') ? 'selected' : '' ?>>Réunion</option>
                            <option value="bureau" <?= (isset($salle_actuelle) && $salle_actuelle['categorie'] == 'bureau') ? 'selected' : '' ?>>Bureau</option>
                            <option value="formation" <?= (isset($salle_actuelle) && $salle_actuelle['categorie'] == 'formation') ? 'selected' : '' ?>>Formation</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-uppercase">Photo</label>
                        <input type="file" name="photo" class="form-control">
                        <?php if(!empty($salle_actuelle['photo'])): ?>
                            <input type="hidden" name="photo_actuelle" value="<?= $salle_actuelle['photo'] ?>">
                            <div class="mt-2"><img src="../photo/<?= $salle_actuelle['photo'] ?>" width="50" class="rounded"></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= $salle_actuelle['description'] ?? '' ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3"><label class="small fw-bold">Pays</label><input type="text" name="pays" class="form-control" value="<?= $salle_actuelle['pays'] ?? 'France' ?>"></div>
                    <div class="col-md-3 mb-3"><label class="small fw-bold">Ville</label><input type="text" name="ville" class="form-control" value="<?= $salle_actuelle['ville'] ?? '' ?>" required></div>
                    <div class="col-md-3 mb-3"><label class="small fw-bold">Code Postal</label><input type="text" name="cp" class="form-control" value="<?= $salle_actuelle['cp'] ?? '' ?>" required></div>
                    <div class="col-md-3 mb-3"><label class="small fw-bold text-primary">Capacité</label><input type="number" name="capacite" class="form-control" value="<?= $salle_actuelle['capacite'] ?? '' ?>" required></div>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?= $salle_actuelle['adresse'] ?? '' ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success px-5 rounded-pill shadow">
                        <i class="bi bi-check-lg me-2"></i> <?= (isset($salle_actuelle)) ? "Confirmer la modification" : "Enregistrer la salle" ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Aperçu</th>
                    <th>Titre</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($salle = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td class="ps-4"><?= $salle['id_salle'] ?></td>
                    <td><img src="../photo/<?= $salle['photo'] ?>" width="60" class="rounded"></td>
                    <td><?= htmlspecialchars($salle['titre']) ?></td>
                    <td><?= htmlspecialchars($salle['ville']) ?></td>
                    <td>
                        <a href="?action=modifier&id_salle=<?= $salle['id_salle'] ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                        <a href="?action=supprimer&id_salle=<?= $salle['id_salle'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>