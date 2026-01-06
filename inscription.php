<?php
require_once("inc/init.inc.php");

// Si le formulaire est validé
if ($_POST) {
    // Vérification si le pseudo existe déjà
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->execute([':pseudo' => $_POST['pseudo']]);

    if ($verif_pseudo->rowCount() > 0) {
        $content .= "<div class='alert alert-danger'>Pseudo déjà utilisé.</div>";
    } else {
        // Hachage du mot de passe
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        // Insertion en BDD
        $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())");
        
        $enregistrement->execute([
            ':pseudo'   => $_POST['pseudo'],
            ':mdp'      => $_POST['mdp'],
            ':nom'      => $_POST['nom'],
            ':prenom'   => $_POST['prenom'],
            ':email'    => $_POST['email'],
            ':civilite' => $_POST['civilite']
        ]);

        $content .= "<div class='alert alert-success'>Inscription réussie ! <a href='connexion.php'>Connectez-vous ici</a></div>";
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 card shadow p-4">
            <h2 class="text-center mb-4">Inscription</h2>
            <?php echo $content = ''; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Pseudo</label>
                    <input type="text" name="pseudo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Civilité</label>
                    <select name="civilite" class="form-control">
                        <option value="m">Homme</option>
                        <option value="f">Femme</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>