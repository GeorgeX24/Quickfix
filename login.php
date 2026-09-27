<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$chyba = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $heslo = $_POST['heslo'] ?? '';

    if (!empty($email) && !empty($heslo)) {
        $stmt = $pdo->prepare("SELECT * FROM uzivatele WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($heslo, $user['heslo'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_jmeno'] = $user['jmeno'];
            $_SESSION['user_role'] = $user['role'];

            header('Location: index.php');
            exit;
        } else {
            $chyba = 'Nespravný e-mail nebo heslo.';
        }
    } else {
        $chyba = 'Vyplňte e-mail i heslo.';
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm p-4 bg-white">
            <h2 class="mb-4 text-center">Přihlášení</h2>

            <?php if ($chyba): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($chyba) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Heslo</label>
                    <input type="password" name="heslo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Přihlásit se</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>