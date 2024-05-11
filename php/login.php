<?php
session_start(); // Démarrer la session

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Vérifier si tous les champs requis sont remplis
    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }

    // Connexion à la base de données
    $host = 'db'; // service name from docker-compose.yml
    $user = 'nada';
    $dbPassword = 'nada2002##';
    $db = 'test_db';

    $conn = new mysqli($host, $user, $dbPassword, $db);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Échapper les données pour éviter les attaques par injection SQL
    $escapedEmail = $conn->real_escape_string($email);

    // Requête pour obtenir le mot de passe hashé de l'utilisateur
    $sql = "SELECT * FROM users WHERE email='$escapedEmail'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Vérifier si le mot de passe entré correspond au mot de passe hashé
        if (password_verify($password, $hashed_password)) {
            // Authentification réussie
            $_SESSION['name'] = $row['name']; // Stocker le nom de l'utilisateur dans la session
            $_SESSION['filiere'] = $row['filiere']; // Stocker l'email de l'utilisateur dans la session
            header("Location: profile.php");
            exit;
        } else {
            // Authentification échouée
            echo "Invalid email or password.";
        }
    } else {
        // Authentification échouée (email non trouvé)
        echo "Invalid email or password.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
} else {
    // Gérer le cas où les données du formulaire ne sont pas soumises
    echo "Email and password are required.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form Data</title>
</head>
<body>
    <!-- Votre formulaire de connexion ici -->
</body>
</html>
