<?php 
require_once("inc/init.inc.php"); 
require_once("inc/header.inc.php"); 
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center mb-5 fw-bold text-primary">Mentions Légales</h1>
            
            <div class="accordion shadow-sm" id="accordionLegal">
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            1. Éditeur du site
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionLegal">
                        <div class="accordion-body">
                            Le site <strong>Lokisalle</strong> est édité par l'entreprise imaginaire <em>Loki Corp</em>, SA au capital de 100 000 €, dont le siège social est situé au 1 rue de la Paix, 75001 Paris.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            2. Hébergement
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionLegal">
                        <div class="accordion-body">
                            Le site est hébergé par <strong>OVH Cloud</strong>. Dans le cadre de ce projet pédagogique, l'environnement de développement est local (XAMPP/WAMP).
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            3. Protection des données (RGPD)
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionLegal">
                        <div class="accordion-body">
                            Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Vos mots de passe sont hachés via l'algorithme <code>password_hash()</code> et ne sont jamais stockés en clair.
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-outline-primary">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>