<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si les données du formulaire sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si tous les champs requis sont remplis
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['old_password'])) {
        echo "All fields are required.";
        exit;
    }

    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $newEmail = $_POST['email'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

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

    // Vérifier si l'ancien mot de passe est correct
    $email = $_SESSION['email'];
    $sql_check_password = "SELECT password FROM users WHERE email='$email'";
    $result = $conn->query($sql_check_password);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($oldPassword, $hashedPassword)) {
            // L'ancien mot de passe est correct, permettre la modification

            // Vérifier si l'email est différent de l'ancien email
            if ($newEmail !== $email) {
                // Vérifier si le nouvel email est disponible
                $sql_check_email = "SELECT * FROM users WHERE email='$newEmail'";
                $result_check_email = $conn->query($sql_check_email);

                if ($result_check_email->num_rows > 0) {
                    echo "Email already exists. Please choose a different one.";
                    exit;
                }
            }

            // Échapper les données pour éviter les attaques par injection SQL
            $escapedEmail = $conn->real_escape_string($email);
            $escapedName = $conn->real_escape_string($name);
            $escapedNewEmail = $conn->real_escape_string($newEmail);

            // Requête SQL pour mettre à jour les informations de l'utilisateur
            $sql = "UPDATE users SET name='$escapedName', email='$escapedNewEmail'";

            // Vérifier si un nouveau mot de passe a été fourni
            if (!empty($newPassword)) {
                // Hasher le nouveau mot de passe
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                // Ajouter la mise à jour du mot de passe à la requête SQL
                $sql .= ", password='$hashedNewPassword'";
            }

            $sql .= " WHERE email='$escapedEmail'";

            // Exécuter la requête SQL
            if ($conn->query($sql) === TRUE) {
                // Mettre à jour les informations de la session si l'email a été modifié
                if ($newEmail !== $email) {
                    $_SESSION['email'] = $newEmail;
                }

                // Mettre à jour les autres informations de la session
                $_SESSION['name'] = $name;

                // Rediriger l'utilisateur vers sa page de profil avec un message de confirmation
                header("Location: profile.php?updated=true");
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Old password is incorrect.";
        }
    } else {
        echo "Error fetching password from database.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
