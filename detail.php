<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT z.*, k.nazev AS kategorie_nazev, u.jmeno AS autor 
                       FROM zavady z
                       JOIN kategorie k ON z.kategorie_id = k.id
                       JOIN uzivatele u ON z.uzivatel_id = u.id
                       WHERE z.id = ?");
$stmt->execute([$id]);
$zavada = $stmt->fetch();

if (!$zavada) {
    echo "<div class='alert alert-danger'>Závada nebyla nalezena.</div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="index.php" class="btn btn-outline-secondary mb-3">&larr; Zpět na seznam</a>
        
        <div class="card shadow-sm p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Závada č. #<?= $zavada['id'] ?></h2>
                <span class="badge bg-primary fs-6"><?= htmlspecialchars($zavada['kategorie_nazev']) ?></span>
            </div>

            <p><strong>Místo:</strong> <?= htmlspecialchars($zavada['misto']) ?></p>
            <p><strong>Nahlásil:</strong> <?= htmlspecialchars($zavada['autor']) ?> (<?= date('d.m.Y H:i', strtotime($zavada['vytvoreno'])) ?>)</p>
            <p><strong>Priorita:</strong> <span class="badge bg-secondary"><?= $zavada['priorita'] ?></span></p>
            <p><strong>Stav:</strong> <span class="badge bg-info text-dark"><?= $zavada['stav'] ?></span></p>

            <hr>
            <h5>Popis problému</h5>
            <p class="bg-light p-3 rounded"><?= nl2br(htmlspecialchars($zavada['popis'])) ?></p>

            <?php if ($zavada['fotografie']): ?>
                <h5 class="mt-4">Fotografie</h5>
                <img src="uploads/<?= htmlspecialchars($zavada['fotografie']) ?>" class="img-fluid rounded border shadow-sm my-2" style="max-height: 400px;" alt="Foto závady">
            <?php endif; ?>

            <?php if ($zavada['poznamka_technika']): ?>
                <hr>
                <h5 class="text-success">Poznámka technika k opravě</h5>
                <p class="bg-light p-3 rounded border-start border-success border-4"><?= nl2br(htmlspecialchars($zavada['poznamka_technika'])) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>