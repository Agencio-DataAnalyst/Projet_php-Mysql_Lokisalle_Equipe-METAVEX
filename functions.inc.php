<?php
// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    if(isset($_SESSION['membre'])) return true;
    else return false;
}

// Fonction pour vérifier si l'utilisateur est admin
function estAdmin() {
    if(estConnecte() && $_SESSION['membre']['statut'] == 1) return true;
    else return false;
}

// Fonction pour debugger (très utile pour toi pendant le dev)
function debug($var) {
    echo '<pre class="bg-dark text-white p-3">';
    print_r($var);
    echo '</pre>';
}