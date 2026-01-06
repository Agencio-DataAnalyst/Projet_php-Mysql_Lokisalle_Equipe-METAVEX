</main> <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4 text-primary">Lokisalle</h5>
                    <p class="small text-muted">
                        Votre partenaire de confiance pour la location de salles professionnelles. 
                        Qualité, flexibilité et prix compétitifs pour tous vos événements.
                    </p>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4">Informations</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= RACINE_SITE ?>mentions_legales.php" class="text-white-50 text-decoration-none small">Mentions Légales</a></li>
                        <li class="mb-2"><a href="<?= RACINE_SITE ?>cgv.php" class="text-white-50 text-decoration-none small">C.G.V (Conditions de vente)</a></li>
                        <li class="mb-2"><a href="<?= RACINE_SITE ?>plan.php" class="text-white-50 text-decoration-none small">Plan du site / Accès</a></li>
                        <li class="mb-2"><a href="<?= RACINE_SITE ?>contact.php" class="text-white-50 text-decoration-none small">Contact</a></li>
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4">Newsletter</h5>
                    <p class="small text-muted mb-3">Inscrivez-vous pour recevoir nos promotions.</p>
                    <form action="<?= RACINE_SITE ?>newsletter.php" method="POST" class="input-group mb-2">
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Votre email" required>
                        <button class="btn btn-primary btn-sm" type="submit">S'inscrire</button>
                    </form>
                    <a href="<?= RACINE_SITE ?>newsletter.php" class="text-white-50 text-decoration-none small italic">En savoir plus sur la newsletter</a>
                </div>
            </div>

            <hr class="my-4 border-secondary">

            <div class="row align-items-center">
                <div class="col-md-7 text-center text-md-start">
                    <p class="small mb-0 text-white-50">
                        &copy; <?= date('Y') ?> - Lokisalle - Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-5 text-center text-md-end mt-3 mt-md-0">
                    <a href="#" class="text-white-50 me-3 fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white-50 me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white-50 fs-5"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?= RACINE_SITE ?>js/scripts.js"></script>

<script>
   (function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="RnicJxxZljgdleGPqpjuf";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>

</body>
</html>