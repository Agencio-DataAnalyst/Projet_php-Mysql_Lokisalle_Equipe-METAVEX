<?php
require_once("inc/init.inc.php");

// 1. Sécurité : Il faut être membre pour laisser un avis
if(!estConnecte()) {
    $content .= "<div class='alert alert-warning'>Vous devez être connecté pour laisser un avis. <a href='connexion.php'>Connexion</a></div>";
}

// 2. Traitement du formulaire
if($_POST && estConnecte()) {
    if(!empty($_POST['commentaire']) && !empty($_POST['note'])) {
        
        $insertion = $pdo->prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_membre, :id_salle, :commentaire, :note, NOW())");
        
        $insertion->execute([
            ':id_membre'   => $_SESSION['membre']['id_membre'],
            ':id_salle'    => $_POST['id_salle'],
            ':commentaire' => $_POST['commentaire'],
            ':note'        => $_POST['note']
        ]);

        $content .= "<div class='alert alert-success'>Merci ! Votre avis a bien été pris en compte.</div>";
    } else {
        $content .= "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
    }
}

// Récupération des salles pour le menu déroulant
$salles = $pdo->query("SELECT id_salle, titre FROM salle ORDER BY titre");

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Votre avis nous intéresse</h3>
                </div>
                <div class="card-body p-4">
                    <?php echo $content; ?>

                    <?php if(estConnecte()): ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Quelle salle avez-vous louée ?</label>
                            <select name="id_salle" class="form-select">
                                <?php while($s = $salles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= $s['id_salle'] ?>"><?= $s['titre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quelle note donneriez-vous ?</label>
                            <select name="note" class="form-select">
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> / 10</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Votre commentaire</label>
                            <textarea name="commentaire" class="form-control" rows="4" placeholder="Partagez votre expérience avec nous..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Envoyer mon avis</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>