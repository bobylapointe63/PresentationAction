<?php
// traitement.php
// Script de traitement du formulaire de contact

// 1. Accès direct interdit : on redirige vers la page d'accueil
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// 2. Adresse de réception (À PERSONNALISER)
$destinataire = 'votre-adresse@example.com'; // 👉 mets ici ton e-mail réel

// 3. Récupération + nettoyage des champs
function get_post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$nom     = get_post('nom');
$email   = get_post('email');
$message = get_post('message');

$errors = [];

// 4. Vérifications simples
if ($nom === '') {
    $errors[] = "Le nom est obligatoire.";
}

if ($email === '') {
    $errors[] = "L'adresse e-mail est obligatoire.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'adresse e-mail n'est pas valide.";
}

if ($message === '') {
    $errors[] = "Le message est obligatoire.";
}

// Si erreurs → on renvoie vers la page avec un statut d’erreur
if (!empty($errors)) {
    header('Location: index.html?status=error#contact');
    exit;
}

// 5. Construction de l’e-mail
$sujet = 'Nouveau message depuis le formulaire de contact du site';
$corps  = "Nom : {$nom}\n";
$corps .= "E-mail : {$email}\n\n";
$corps .= "Message :\n{$message}\n";

// 6. En-têtes de l’e-mail
$headers  = "From: \"Formulaire\" <{$destinataire}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// 7. Envoi de l’e-mail
if (mail($destinataire, $sujet, $corps, $headers)) {
    // Succès → on renvoie sur la page avec statut success
    header('Location: index.html?status=success#contact');
    exit;
} else {
    // Échec → on renvoie avec statut error
    header('Location: index.html?status=error#contact');
    exit;
}
