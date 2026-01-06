<?php
require_once("inc/init.inc.php");
require_once("inc/header.inc.php");
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h1 class="text-center fw-bold mb-4">Conditions Générales de Vente</h1>
                    <p class="text-muted text-center mb-5">En vigueur au <?= date('d/m/Y') ?></p>

                    <div class="accordion accordion-flush" id="accordionCGV">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#art1">
                                    Article 1 - Objet
                                </button>
                            </h2>
                            <div id="art1" class="accordion-collapse collapse show" data-bs-parent="#accordionCGV">
                                <div class="accordion-body text-muted">
                                    Les présentes CGV régissent les relations contractuelles entre la plateforme **LOKISALLE** et toute personne effectuant une réservation de salle sur le site. La validation d'une commande implique l'acceptation sans réserve des présentes conditions.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#art2">
                                    Article 2 - Processus de réservation
                                </button>
                            </h2>
                            <div id="art2" class="accordion-collapse collapse" data-bs-parent="#accordionCGV">
                                <div class="accordion-body text-muted">
                                    L'utilisateur choisit une salle et un créneau parmi les offres disponibles. La réservation n'est considérée comme définitive qu'après confirmation du paiement et réception de l'e-mail de validation.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#art3">
                                    Article 3 - Prix et Paiement
                                </button>
                            </h2>
                            <div id="art3" class="accordion-collapse collapse" data-bs-parent="#accordionCGV">
                                <div class="accordion-body text-muted">
                                    Les prix sont indiqués en Euros Hors Taxes (HT) sur les fiches produits et majorés de la TVA en vigueur (20%) lors du récapitulatif final. Le paiement s'effectue intégralement lors de la réservation.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#art4">
                                    Article 4 - Annulation et Rétractation
                                </button>
                            </h2>
                            <div id="art4" class="accordion-collapse collapse" data-bs-parent="#accordionCGV">
                                <div class="accordion-body text-muted">
                                    Conformément à la loi, le droit de rétractation ne s'applique pas aux prestations de services de transport, d'hébergement ou de loisirs fournies à une date ou à une période déterminée. Toute annulation devra faire l'objet d'un accord spécifique avec l'administration.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#art5">
                                    Article 5 - Données personnelles
                                </button>
                            </h2>
                            <div id="art5" class="accordion-collapse collapse" data-bs-parent="#accordionCGV">
                                <div class="accordion-body text-muted">
                                    Les informations collectées lors de l'inscription sont nécessaires à la gestion des commandes. Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données via votre espace "Profil".
                                </div>
                            </div>
                        </div>

                    </div> <div class="mt-5 p-4 bg-light rounded text-center">
                        <p class="mb-0 small">Pour toute question relative aux CGV, contactez-nous via notre <a href="contact.php">formulaire de contact</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>