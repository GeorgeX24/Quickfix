<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Přístup odepřen. Tuto stránku mohou zobrazit pouze administrátoři.</div>";
    require_once 'includes/footer.php';
    exit;
}

$zprava = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zavada_id'])) {
    $zavada_id = $_POST['zavada_id'];
    $novy_stav = $_POST['stav'];
    $poznamka = trim($_POST['poznamka_technika']);

    $stmt = $pdo->prepare("UPDATE zavady SET stav = ?, poznamka_technika = ? WHERE id = ?");
    if ($stmt->execute([$novy_stav, $poznamka, $zavada_id])) {
        $zprava = 'Změna stavu byla úspěšně uložena.';
    }
}

$zavady = $pdo->query("SELECT z.*, k.nazev AS kategorie_nazev, u.jmeno AS autor 
                       FROM zavady z
                       JOIN kategorie k ON z.kategorie_id = k.id
                       JOIN uzivatele u ON z.uzivatel_id = u.id
                       ORDER BY z.vytvoreno DESC")->fetchAll();
?>

<h1 class="mb-4">Administrace a správa závad</h1>

<?php if ($zprava): ?>
    <div class="alert alert-success"><?= htmlspecialchars($zprava) ?></div>
<?php endif; ?>

<div class="row">
    <?php foreach ($zavady as $z): ?>
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><strong>Závada #<?= $z['id'] ?>:</strong> <?= htmlspecialchars($z['kategorie_nazev']) ?> - <?= htmlspecialchars($z['misto']) ?></span>
                    <small><?= date('d.m.Y H:i', strtotime($z['vytvoreno'])) ?> | Nahlásil: <?= htmlspecialchars($z['autor']) ?></small>
                </div>
                <div class="card-body">
                    <p><strong>Popis:</strong> <?= htmlspecialchars($z['popis']) ?></p>
                    
                    <form method="POST" class="row g-3 align-items-end border-top pt-3">
                        <input type="hidden" name="zavada_id" value="<?= $z['id'] ?>">
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Změnit stav</label>
                            <select name="stav" class="form-select">
                                <option value="Nahlášeno" <?= $z['stav'] === 'Nahlášeno' ? 'selected' : '' ?>>Nahlášeno</option>
                                <option value="V řešení" <?= $z['stav'] === 'V řešení' ? 'selected' : '' ?>>V řešení</option>
                                <option value="Opraveno" <?= $z['stav'] === 'Opraveno' ? 'selected' : '' ?>>Opraveno</option>
                            </select>
                        </div>
                        
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Poznámka technika</label>
                            <input type="text" name="poznamka_technika" class="form-control" value="<?= htmlspecialchars($z['poznamka_technika'] ?? '') ?>" placeholder="Např. Oprava naplánována na středu...">
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">Uložit změny</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>