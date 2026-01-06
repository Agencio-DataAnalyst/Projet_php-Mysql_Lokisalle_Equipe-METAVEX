<?php
require_once("inc/init.inc.php");

// Traitement du formulaire
if($_POST) {
    if(!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        
        $insertion = $pdo->prepare("INSERT INTO contact (nom, email, objet, message, date_enregistrement) VALUES (:nom, :email, :objet, :message, NOW())");
        
        $insertion->execute([
            ':nom'     => $_POST['nom'],
            ':email'    => $_POST['email'],
            ':objet'    => $_POST['objet'],
            ':message'  => $_POST['message']
        ]);

        $content .= "<div class='alert alert-success fw-bold text-center'>Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.</div>";
    } else {
        $content .= "<div class='alert alert-danger'>Veuillez remplir tous les champs obligatoires.</div>";
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-5 mb-4">
            <h2 class="display-5 fw-bold text-primary">Contactez-nous</h2>
            <p class="lead text-muted">Une question sur une salle ? Besoin d'un devis sur mesure ? Notre équipe est à votre écoute.</p>
            <ul class="list-unstyled mt-4">
                <li class="mb-2">📍 12 rue de la Paix, 75002 Paris</li>
                <li class="mb-2">📞 01 23 45 67 89</li>
                <li class="mb-2">📧 contact@lokisalle.fr</li>
            </ul>
        </div>

        <div class="col-md-7">
            <div class="card shadow-lg border-0 p-4">
                <?php echo $content = ''; ?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Votre Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Votre Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objet</label>
                        <select name="objet" class="form-select">
                            <option value="Réservation">Question sur une réservation</option>
                            <option value="Facturation">Facturation</option>
                            <option value="Autre">Autre demande</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Envoyer mon message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>