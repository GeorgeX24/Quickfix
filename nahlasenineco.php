<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$chyba = '';
$uspech = '';

$kategorie = $pdo->query("SELECT * FROM kategorie")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategorie_id = $_POST['kategorie_id'] ?? '';
    $misto = trim($_POST['misto'] ?? '');
    $popis = trim($_POST['popis'] ?? '');
    $priorita = $_POST['priorita'] ?? 'Střední';
    $fotografie_nazev = null;

    if (empty($kategorie_id) || empty($misto) || empty($popis)) {
        $chyba = 'Vyplňte prosím všechna povinná pole.';
    } else {
        if (isset($_FILES['fotografie']) && $_FILES['fotografie']['error'] === UPLOAD_ERR_OK) {
            $tmp_path = $_FILES['fotografie']['tmp_name'];
            $nazev_souboru = $_FILES['fotografie']['name'];
            $ext = strtolower(pathinfo($nazev_souboru, PATHINFO_EXTENSION));
            $povolene_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $povolene_ext)) {
                $fotografie_nazev = uniqid('zavada_') . '.' . $ext;
                $cilova_slozka = 'uploads/';

                if (!is_dir($cilova_slozka)) {
                    mkdir($cilova_slozka, 0777, true);
                }

                move_uploaded_file($tmp_path, $cilova_slozka . $fotografie_nazev);
            } else {
                $chyba = 'Neplatný formát obrázku. Povolené jsou JPG, PNG, GIF a WEBP.';
            }
        }

        if (empty($chyba)) {
            $stmt = $pdo->prepare("INSERT INTO zavady (uzivatel_id, kategorie_id, popis, misto, priorita, fotografie) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$_SESSION['user_id'], $kategorie_id, $popis, $misto, $priorita, $fotografie_nazev])) {
                $uspech = 'Závada byla úspěšně nahlášena!';
            } else {
                $chyba = 'Při ukládání došlo k ошибce.';
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm p-4 bg-white">
            <h2 class="mb-4">Nahlásit novou závadu</h2>

            <?php if ($chyba): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($chyba) ?></div>
            <?php endif; ?>
            <?php if ($uspech): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($uspech) ?> 
                    <a href="index.php" class="alert-link">Přejít na přehled závad</a>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategorie závady *</label>
                    <select name="kategorie_id" class="form-select" required>
                        <option value="">-- Vyberte kategorii --</option>
                        <?php foreach ($kategorie as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nazev']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Místo závady *</label>
                    <input type="text" name="misto" class="form-control" placeholder="např. Učebna č. 12, 2. patro" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Priorita</label>
                    <select name="priorita" class="form-select">
                        <option value="Nízká">Nízká</option>
                        <option value="Střední" selected>Střední</option>
                        <option value="Vysoká">Vysoká</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Popis problému *</label>
                    <textarea name="popis" class="form-control" rows="4" placeholder="Podrobně popište vzniklou závadu..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Fotografie závady</label>
                    <input type="file" name="fotografie" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary w-100">Odeslat hlášení</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>