<?php
include('db-tilkobling.php');
$melding = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = $_POST['klassekode'] ?? '';
    if ($klassekode !== '') {
        $kode = mysqli_real_escape_string($db, $klassekode);
        // Finnes klassen?
        $finn = mysqli_query($db, "SELECT 1 FROM klasse WHERE klassekode = '$kode'");
        if (!$finn || mysqli_num_rows($finn) === 0) {
            $melding = 'Klassen finnes ikke.';
        } else {
            // Har klassen studenter?
            $cntRes = mysqli_query($db, "SELECT COUNT(*) AS c FROM student WHERE klassekode = '$kode'");
            $rad = $cntRes ? mysqli_fetch_assoc($cntRes) : ['c' => 0];
            if (!empty($rad['c']) && (int)$rad['c'] > 0) {
                $melding = 'Kan ikke slette klasse med studenter i.';
            } else {
                $sql = "DELETE FROM klasse WHERE klassekode = '$kode'";
                if (mysqli_query($db, $sql)) {
                    if (mysqli_affected_rows($db) > 0) {
                        $melding = 'Klasse er slettet.';
                    } else {
                        $melding = 'Fant ikke klassekode.';
                    }
                } else {
                    $melding = 'Feil: ' . mysqli_error($db);
                }
            }
        }
    } else {
        $melding = 'Velg en klasse.';
    }
}
$klasser = mysqli_query($db, "SELECT klassekode FROM klasse ORDER BY klassekode");
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett klasse</title>
    <script src="funksjoner.js"></script>
    <script>
      document.addEventListener('submit', function(e){
        e.stopImmediatePropagation();
        if (!bekreft()) { e.preventDefault(); }
      }, true);
    </script>
</head>
<body>
    <h1>Slett klasse</h1>
    <?php if ($melding !== ''): ?>
        <p><?php echo htmlspecialchars($melding); ?></p>
    <?php endif; ?>
    <form method="post" action="" onsubmit="return confirm('Er du sikker på at du vil slette denne klassen?');">
        <label for="klassekode">Klassekode</label>
        <select name="klassekode" id="klassekode" required>
            <option value="">-- Velg klasse --</option>
            <?php if ($klasser): ?>
                <?php while ($rad = mysqli_fetch_assoc($klasser)): ?>
                    <option value="<?php echo htmlspecialchars($rad['klassekode']); ?>">
                        <?php echo htmlspecialchars($rad['klassekode']); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
        <br>
        <button type="submit">Slett</button>
    </form>
</body>
</html>
