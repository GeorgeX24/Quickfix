<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$chyba = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = trim($_POST['jmeno'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $heslo = $_POST['heslo'] ?? '';

    if (!empty($jmeno) && !empty($email) && !empty($heslo)) {
        // Kontrola, zda e-mail už neexistuje
        $stmt = $pdo->prepare("SELECT id FROM uzivatele WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $chyba = 'Uživatel s tímto e-mailem již existuje.';
        } else {
            $hashedPassword = password_hash($heslo, PASSWORD_BCRYPT);
            $insert = $pdo->prepare("INSERT INTO uzivatele (jmeno, email, heslo) VALUES (?, ?, ?)");
            if ($insert->execute([$jmeno, $email, $hashedPassword])) {
                header('Location: login.php');
                exit;
            } else {
                $chyba = 'Registrace se nezdařila.';
            }
        }
    } else {
        $chyba = 'Vyplňte všechna pole.';
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm p-4 bg-white">
            <h2 class="mb-4 text-center">Registrace</h2>

            <?php if ($chyba): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($chyba) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Jméno a příjmení</label>
                    <input type="text" name="jmeno" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Heslo</label>
                    <input type="password" name="heslo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Zaregistrovat se</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>