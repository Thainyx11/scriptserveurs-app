<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    echo "Tentative d'inscription pour : " . $email . "<br>";

    // Préparer la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Inscription réussie ! Utilisateur ajouté à la base de données.<br>";
        echo "Hash du mot de passe stocké : " . $hashed_password . "<br>";
    } else {
        echo "Erreur lors de l'inscription : " . $stmt->error . "<br>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Mon application web</title>
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
        <h1>Inscription</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" name="submit" value="S'inscrire">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 - Mon application web</p>
    </footer>
</body>
</html>