<?php
require_once("inc/init.inc.php");

// 1. Traitement de l'inscription (POST)
if ($_POST) {
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];

        // On vérifie si l'email n'est pas déjà inscrit
        $selection = $pdo->prepare("SELECT * FROM newsletter WHERE email = :email");
        $selection->execute([':email' => $email]);

        if ($selection->rowCount() > 0) {
            $content .= "<div class='alert alert-info text-center'>Vous êtes déjà inscrit à notre newsletter avec l'adresse : <strong>$email</strong>.</div>";
        } else {
            // Insertion en BDD (Assure-toi d'avoir une table 'newsletter' avec les colonnes id_newsletter et email)
            $insertion = $pdo->prepare("INSERT INTO newsletter (email) VALUES (:email)");
            $insertion->execute([':email' => $email]);
            
            $content .= "<div class='alert alert-success text-center'>Félicitations ! Votre inscription à la newsletter de <strong>Lokisalle</strong> a bien été prise en compte.</div>";
        }
    } else {
        $content .= "<div class='alert alert-danger text-center'>Veuillez saisir une adresse email valide.</div>";
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-paper-heart text-primary" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="fw-bold mb-3">Restez connectés !</h1>
                    <p class="lead text-muted mb-4">Inscrivez-vous pour recevoir en avant-première nos offres exclusives, nos nouveaux bureaux disponibles et nos codes promotionnels.</p>

                    <?= $content = ''?>

                    <form method="post" class="mt-4">
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <div class="input-group input-group-lg shadow-sm">
                                    <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                                    <button class="btn btn-primary px-4" type="submit">S'abonner</button>
                                </div>
                                <p class="text-muted small mt-3 italic">
                                    <i class="bi bi-shield-lock me-1"></i> Nous respectons votre vie privée. Vous pouvez vous désinscrire à tout moment.
                                </p>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-5">
                        <div class="col-md-4">
                            <i class="bi bi-megaphone fs-3 text-primary"></i>
                            <p class="small fw-bold mt-2">Alertes Offres</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-percent fs-3 text-primary"></i>
                            <p class="small fw-bold mt-2">Promotions</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-calendar-event fs-3 text-primary"></i>
                            <p class="small fw-bold mt-2">Événements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>