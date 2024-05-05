<?php
// Vérifier si le formulaire d'inscription a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

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

    // Vérifier si le nom d'utilisateur est déjà utilisé
    $sql_check_user = "SELECT * FROM users WHERE username='$username'";
    $result_check_user = $conn->query($sql_check_user);

    if ($result_check_user->num_rows > 0) {
        echo "Ce nom d'utilisateur est déjà utilisé.";
    } else {
        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer les informations de l'utilisateur dans la base de données
        $sql_insert_user = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        
        if ($conn->query($sql_insert_user) === TRUE) {
            echo "Inscription réussie!";
        } else {
            echo "Erreur lors de l'inscription: " . $conn->error;
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>

