<?php
require_once("inc/init.inc.php");
require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Plan du site et Accès</h1>
        <p class="text-muted">Retrouvez toutes les rubriques de notre plateforme et notre emplacement.</p>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title h5 fw-bold mb-4 text-primary">
                        <i class="bi bi-diagram-3 me-2"></i>Navigation
                    </h3>
                    
                    <ul class="list-unstyled ps-3">
                        <li class="mb-2">
                            <a href="index.php" class="text-dark fw-bold text-decoration-none">Accueil</a>
                            <ul class="list-unstyled ps-4 mt-2 small">
                                <li><a href="recherche.php" class="text-muted text-decoration-none">Rechercher une salle</a></li>
                                <li><a href="contact.php" class="text-muted text-decoration-none">Nous contacter</a></li>
                            </ul>
                        </li>
                        
                        <li class="mb-2 mt-3">
                            <span class="text-dark fw-bold">Espace Membre</span>
                            <ul class="list-unstyled ps-4 mt-2 small">
                                <?php if(estConnecte()): ?>
                                    <li><a href="profil.php" class="text-muted text-decoration-none">Mon profil & Réservations</a></li>
                                    <li><a href="connexion.php?action=deconnexion" class="text-muted text-decoration-none">Déconnexion</a></li>
                                <?php else: ?>
                                    <li><a href="connexion.php" class="text-muted text-decoration-none">Connexion</a></li>
                                    <li><a href="inscription.php" class="text-muted text-decoration-none">Inscription</a></li>
                                    <li><a href="motdepasseperdu.php" class="text-muted text-decoration-none">Mot de passe oublié</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="mb-2 mt-3">
                            <span class="text-dark fw-bold">Informations Légales</span>
                            <ul class="list-unstyled ps-4 mt-2 small">
                                <li><a href="mentions_legales.php" class="text-muted text-decoration-none">Mentions Légales</a></li>
                                <li><a href="cgv.php" class="text-muted text-decoration-none">C.G.V.</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title h5 fw-bold mb-4 text-primary">
                        <i class="bi bi-geo-alt me-2"></i>Où nous trouver ?
                    </h3>
                    
                    <div class="ratio ratio-16x9 mb-3 rounded overflow-hidden shadow-sm">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937595!2d2.2922926156743895!3d48.85837007928746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca979aed86683!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1620000000000!5m2!1sfr!2sfr" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    
                    <div class="bg-light p-3 rounded">
                        <p class="mb-1 fw-bold">Siège Social Lokisalle</p>
                        <p class="small text-muted mb-0">1 Rue de la Paix, 75001 Paris</p>
                        <p class="small text-muted">Tel : 01 02 03 04 05</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>