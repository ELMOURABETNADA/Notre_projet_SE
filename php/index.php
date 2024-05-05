<?php
// Récupérer les données du formulaire ou de l'URL
$username = isset($_POST['username']) ? $_POST['username'] : (isset($_GET['username']) ? $_GET['username'] : null);
$password = isset($_POST['password']) ? $_POST['password'] : (isset($_GET['password']) ? $_GET['password'] : null);

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

// Vérifier si les données du formulaire ou de l'URL sont définies
if (isset($username, $password)) {
    // Échapper les données pour éviter les attaques par injection SQL
    $escapedUsername = $conn->real_escape_string($username);

    // Requête pour obtenir le mot de passe hashé de l'utilisateur
    $sql = "SELECT password FROM users WHERE username='$escapedUsername'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Vérifier si le mot de passe entré correspond au mot de passe hashé
        if (password_verify($password, $hashed_password)) {
            // Authentification réussie
            echo "Login successful!";
        } else {
            // Authentification échouée
            echo "Invalid username or password.";
        }
    } else {
        // Authentification échouée (nom d'utilisateur non trouvé)
        echo "Invalid username or password.";
    }
} else {
    // Gérer le cas où les données du formulaire ou de l'URL ne sont pas définies
    echo "Username and password are required.";
}

// Fermer la connexion à la base de données
$conn->close();
?>

