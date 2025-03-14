<?php
session_start();
require 'php/db.php';

if (isset($_SESSION['is_admin'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$conn) {
        $error = "Błąd serwera. Spróbuj ponownie później.";
    } else {
        $stmt = $conn->prepare("SELECT username, password, is_admin FROM users WHERE username = ?");
        if ($stmt === false) {
            $error = "Błąd serwera. Spróbuj ponownie później.";
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['is_admin'] = $user['is_admin'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Nieprawidłowe hasło!";
                }
            } else {
                $error = "Nie znaleziono użytkownika!";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="login-container">
        <h2>Zaloguj się</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zaloguj</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>