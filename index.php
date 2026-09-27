<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$kategorie = $pdo->query("SELECT * FROM kategorie")->fetchAll();

$sql = "SELECT z.*, k.nazev AS kategorie_nazev, u.jmeno AS autor 
        FROM zavady z
        JOIN kategorie k ON z.kategorie_id = k.id
        JOIN uzivatele u ON z.uzivatel_id = u.id
        WHERE 1=1";

$params = [];

if (!empty($_GET['kategorie'])) {
    $sql .= " AND z.kategorie_id = ?";
    $params[] = $_GET['kategorie'];
}
if (!empty($_GET['priorita'])) {
    $sql .= " AND z.priorita = ?";
    $params[] = $_GET['priorita'];
}
if (!empty($_GET['stav'])) {
    $sql .= " AND z.stav = ?";
    $params[] = $_GET['stav'];
}

$sql .= " ORDER BY z.vytvoreno DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$zavady = $stmt->fetchAll();
?>

<h1 class="mb-4">Přehled hlášených závad</h1>

<form method="GET" class="row g-3 mb-4 bg-white p-3 rounded shadow-sm">
    <div class="col-md-3">
        <label class="form-label">Kategorie</label>
        <select name="kategorie" class="form-select">
            <option value="">Všechny kategorie</option>
            <?php foreach ($kategorie as $kat): ?>
                <option value="<?= $kat['id'] ?>" <?= ($_GET['kategorie'] ?? '') == $kat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['nazev']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Priorita</label>
        <select name="priorita" class="form-select">
            <option value="">Všechny priority</option>
            <option value="Nízká" <?= ($_GET['priorita'] ?? '') === 'Nízká' ? 'selected' : '' ?>>Nízká</option>
            <option value="Střední" <?= ($_GET['priorita'] ?? '') === 'Střední' ? 'selected' : '' ?>>Střední</option>
            <option value="Vysoká" <?= ($_GET['priorita'] ?? '') === 'Vysoká' ? 'selected' : '' ?>>Vysoká</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stav</label>
        <select name="stav" class="form-select">
            <option value="">Všechny stavy</option>
            <option value="Nahlášeno" <?= ($_GET['stav'] ?? '') === 'Nahlášeno' ? 'selected' : '' ?>>Nahlášeno</option>
            <option value="V řešení" <?= ($_GET['stav'] ?? '') === 'V řešení' ? 'selected' : '' ?>>V řešení</option>
            <option value="Opraveno" <?= ($_GET['stav'] ?? '') === 'Opraveno' ? 'selected' : '' ?>>Opraveno</option>
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrovat</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>Datum</th>
                <th>Kategorie</th>
                <th>Místo</th>
                <th>Popis</th>
                <th>Priorita</th>
                <th>Stav</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($zavady) > 0): ?>
                <?php foreach ($zavady as $z): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($z['vytvoreno'])) ?></td>
                        <td><?= htmlspecialchars($z['kategorie_nazev']) ?></td>
                        <td><?= htmlspecialchars($z['misto']) ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($z['popis'], 0, 50, '...')) ?></td>
                        <td>
                            <?php
                            $badgeP = 'secondary';
                            if ($z['priorita'] === 'Vysoká') $badgeP = 'danger';
                            if ($z['priorita'] === 'Střední') $badgeP = 'warning text-dark';
                            if ($z['priorita'] === 'Nízká') $badgeP = 'info';
                            ?>
                            <span class="badge bg-<?= $badgeP ?>"><?= $z['priorita'] ?></span>
                        </td>
                        <td>
                            <?php
                            $badgeS = 'warning text-dark';
                            if ($z['stav'] === 'V řešení') $badgeS = 'info';
                            if ($z['stav'] === 'Opraveno') $badgeS = 'success';
                            ?>
                            <span class="badge bg-<?= $badgeS ?>"><?= $z['stav'] ?></span>
                        </td>
                        <td>
                            <a href="detail.php?id=<?= $z['id'] ?>" class="btn btn-sm btn-outline-secondary">Zobrazit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Žádné závady nebyly nalezeny.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>