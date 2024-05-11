<?php
session_start(); // Démarrez la session

// Vérifiez si le nom, l'email et l'image de la filière sont définis dans la session
if (isset($_SESSION['name'], $_SESSION['filiere'], $_SESSION['filiere_image'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>
        <style>
            :root {
            --standard: #003366;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('<?php echo $_SESSION['filiere_image']; ?>');
            background-size: cover;
        }

        h1 {
            color: var(--standard);
            text-align: center;
            margin-top: 50px;
        }

        .profile-info {
            width: 300px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info p {
            margin-bottom: 10px;
        }

        .profile-info a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: var(--standard);
            border: 1px solid var(--standard);
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .profile-info a:hover {
            background-color: var(--standard);
            color: #fff;
        }

        /* Style des boutons */
        .profile-info .action-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .profile-info .action-buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background-color: var(--standard);
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .profile-info .action-buttons a:hover {
            background-color: #001f4d;
        }
        </style>
    </head>
    <body>
        <h1>Bienvenue <?php echo $_SESSION['name']; ?> à ton profil</h1>

        <div class="profile-info">
            <?php
            // Afficher les informations du profil de l'utilisateur
            echo "<p>Vous êtes : " . $_SESSION['filiere'] . "</p>";
            ?>
            <div class="action-buttons">
                <a href="edit_profile.html">Edit</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Rediriger vers la page de login si les informations de session sont manquantes
    header("Location: login.php");
    exit;
}
?>
