<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données (comme dans register.php)
$servername = "localhost";
$username = "u586703453_ahmedhusain199";
$password = "Ahmed11.";
$database = "u586703453_projet";

$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion à la base de données échouée : " . $conn->connect_error);
}

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    echo "Tentative de connexion pour l'email : " . $email . "<br>";

    // Préparer la requête de sélection
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "Nombre de résultats trouvés : " . $result->num_rows . "<br>";

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Hash du mot de passe stocké : " . $row["password"] . "<br>";
        echo "Vérification du mot de passe : " . (password_verify($password, $row["password"]) ? "Réussi" : "Échoué") . "<br>";
        
        if (password_verify($password, $row["password"])) {
            echo "Mot de passe correct !<br>";
            // Démarrer la session et stocker les informations de l'utilisateur
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            $_SESSION["user_email"] = $email;
            header("Location: profile.php");
            exit;
        } else {
            echo "Mot de passe incorrect.<br>";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.<br>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mon application web</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="register.php">Inscription</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Connexion</h1>
        <form id="login-form" method="post" action="login.php">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Se connecter">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 - Mon application web</p>
    </footer>
</body>
</html>