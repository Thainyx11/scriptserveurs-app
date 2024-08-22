<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";
$username = "u586703453_ahmedhusain199";
$password = "Ahmed11.";
$database = "u586703453_projet";

$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion à la base de données échouée : " . $conn->connect_error);
}

$message = "";

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_email"])) {
        $new_email = $conn->real_escape_string($_POST["new_email"]);
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $new_email, $_SESSION["user_id"]);
        if ($stmt->execute()) {
            $_SESSION["user_email"] = $new_email;
            $message = "Adresse e-mail mise à jour avec succès.";
        } else {
            $message = "Erreur lors de la mise à jour de l'adresse e-mail.";
        }
        $stmt->close();
    } elseif (isset($_POST["update_password"])) {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        
        // Vérifier le mot de passe actuel
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($current_password, $user["password"])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION["user_id"]);
            if ($stmt->execute()) {
                $message = "Mot de passe mis à jour avec succès.";
            } else {
                $message = "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            $message = "Le mot de passe actuel est incorrect.";
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Mon application web</title>
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
        <h1>Profil</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <p>Nom : <?php echo $_SESSION["user_name"]; ?></p>
        <p>Email : <?php echo $_SESSION["user_email"]; ?></p>
        
        <h2>Modifier l'adresse e-mail</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="new_email">Nouvelle adresse e-mail :</label>
            <input type="email" id="new_email" name="new_email" required>
            <input type="submit" name="update_email" value="Mettre à jour l'e-mail">
        </form>
        
        <h2>Modifier le mot de passe</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="current_password">Mot de passe actuel :</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required>
            <input type="submit" name="update_password" value="Mettre à jour le mot de passe">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 - Mon application web</p>
    </footer>
</body>
</html>