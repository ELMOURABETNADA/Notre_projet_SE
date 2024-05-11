<?php
session_start(); // Démarrer la session

// Récupérer les données du formulaire
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$birthday = $_POST['dob'];
$gender = $_POST['gender'];
$filiere = $_POST['filiere'];

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

// Vérifier si les mots de passe correspondent
if ($password !== $confirmPassword) {
    echo "Les mots de passe ne correspondent pas.";
    exit();
}

// Hasher le mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insérer les informations de l'utilisateur dans la base de données
$sql_insert_user = "INSERT INTO users (name, email, dob, gender, filiere, password) VALUES ('$name', '$email', '$birthday', '$gender', '$filiere', '$hashed_password')";

if ($conn->query($sql_insert_user) === TRUE) {
    // Stocker le chemin de l'image de la filière dans la session
    if ($filiere === "Genie informatique") {
        $_SESSION['filiere_image'] = "GI.jpg";
    } elseif ($filiere === "Genie civil") {
        $_SESSION['filiere_image'] = "GC.jpg";
    } elseif ($filiere === "Genie mecatronique") {
        $_SESSION['filiere_image'] = "GM.jpg";
    } elseif ($filiere === "Supply chain managemant") {
        $_SESSION['filiere_image'] = "scm.jpg";
    } elseif ($filiere === "Genie telecomunication et reseaux") {
        $_SESSION['filiere_image'] = "GSTR.jpg";
    }
    header("Location: home.html");
    exit();
} else {
    echo "Erreur lors de l'inscription: " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
?>
profile.php: } else {
    echo "Erreur lors de l'inscription: " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
