<?php
require_once("inc/init.inc.php");

// 1. Déconnexion
if(isset($_GET['action']) && $_GET['action'] == "deconnexion") {
    session_destroy();
    header("location:connexion.php");
    exit();
}

// 2. Redirection si déjà connecté
if(estConnecte()) {
    header("location:profil.php");
    exit();
}

// 3. Traitement du formulaire
if($_POST) {
    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $resultat->execute();

    if($resultat->rowCount() != 0) {
        $membre = $resultat->fetch(PDO::FETCH_ASSOC);
        
        if(password_verify($_POST['mdp'], $membre['mdp'])) {
            foreach($membre as $indice => $valeur) {
                if($indice != 'mdp') {
                    $_SESSION['membre'][$indice] = $valeur;
                }
            }
            
            // --- DIFFÉRENCE ADMIN / MEMBRE ---
            if(estAdmin()) {
                header("location:admin/statistiques.php");
            } else {
                header("location:profil.php");
            }
            exit();

        } else {
            $contenu .= '<div class="alert alert-danger text-center">Erreur sur le mot de passe.</div>';
        }
    } else {
        $contenu .= '<div class="alert alert-danger text-center">Pseudo inexistant.</div>';
    }
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    
                    <div class="col-md-6 p-5 border-end">
                        <h3 class="fw-bold mb-4">Déjà membre ?</h3>
                        
                        <?= $contenu = '' ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="pseudo" class="form-label">Pseudo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" id="pseudo" name="pseudo" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" id="mdp" name="mdp" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label small text-muted">Se souvenir de moi</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Se connecter</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="motdepasseperdu.php" class="text-decoration-none small">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <div class="col-md-6 p-5 bg-light d-flex flex-column justify-content-center text-center">
                        <h3 class="fw-bold mb-3">Pas encore membre ?</h3>
                        <p class="text-muted mb-4">Rejoignez LOKISALLE pour réserver vos espaces de travail en quelques clics.</p>
                        <a href="inscription.php" class="btn btn-outline-dark btn-lg">Créer un compte</a>
                        
                        <div class="mt-5 small text-muted

