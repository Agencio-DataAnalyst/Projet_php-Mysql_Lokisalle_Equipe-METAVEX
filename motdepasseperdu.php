<?php
require_once("inc/init.inc.php");

if($_POST) {
    // 1. On vérifie si l'email existe en BDD
    $resultat = $pdo->prepare("SELECT * FROM membre WHERE email = :email");
    $resultat->execute([':email' => $_POST['email']]);

    if($resultat->rowCount() > 0) {
        // Dans la réalité, on générerait un token et on enverrait un mail ici.
        // Pour ton projet, on affiche un message de succès simulé.
        $content .= "<div class='alert alert-success text-center'>
                        Un e-mail de récupération a été envoyé à <strong>" . htmlspecialchars($_POST['email']) . "</strong>.<br>
                        (Simulation : Dans un environnement de production, un lien sécurisé serait généré).
                     </div>";
    } else {
        $content .= "<div class='alert alert-danger text-center'>Désolé, aucun compte n'est associé à cette adresse e-mail.</div>";
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Mot de passe oublié ?</h4>
                </div>
                <div class="card-body p-4">
                    <?= $content ?>
                    
                    <p class="text-muted small mb-4">
                        Saisissez l'adresse e-mail associée à votre compte. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>

                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Votre adresse e-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="exemple@mail.com" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Envoyer le lien</button>
                            <a href="connexion.php" class="btn btn-link text-decoration-none">Retour à la connexion</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>