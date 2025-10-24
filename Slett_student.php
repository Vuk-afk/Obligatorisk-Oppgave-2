<?php
include('db-tilkobling.php');
$melding = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = $_POST['brukernavn'] ?? '';
    if ($brukernavn !== '') {
        $bn = mysqli_real_escape_string($db, $brukernavn);
        $finn = mysqli_query($db, "SELECT 1 FROM student WHERE brukernavn = '$bn'");
        if (!$finn || mysqli_num_rows($finn) === 0) {
            $melding = 'Studenten finnes ikke.';
        } else {
            $sql = "DELETE FROM student WHERE brukernavn = '$bn'";
            if (mysqli_query($db, $sql)) {
                if (mysqli_affected_rows($db) > 0) {
                    $melding = 'Student er slettet.';
                } else {
                    $melding = 'Fant ikke brukernavn.';
                }
            } else {
                $melding = 'Feil: ' . mysqli_error($db);
            }
        }
    } else {
        $melding = 'Velg en student.';
    }
}
$studenter = mysqli_query($db, "SELECT brukernavn FROM student ORDER BY brukernavn");
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett student</title>
    <script src="funksjoner.js"></script>
    <script>
      document.addEventListener('submit', function(e){
        e.stopImmediatePropagation();
        if (!bekreft()) { e.preventDefault(); }
      }, true);
    </script>
</head>
<body>
    <h1>Slett student</h1>
    <?php if ($melding !== ''): ?>
        <p><?php echo htmlspecialchars($melding); ?></p>
    <?php endif; ?>
    <form method="post" action="" onsubmit="return confirm('Er du sikker på at du vil slette denne studenten?');">
        <label for="brukernavn">Brukernavn</label>
        <select name="brukernavn" id="brukernavn" required>
            <option value="">-- Velg student --</option>
            <?php if ($studenter): ?>
                <?php while ($rad = mysqli_fetch_assoc($studenter)): ?>
                    <option value="<?php echo htmlspecialchars($rad['brukernavn']); ?>">
                        <?php echo htmlspecialchars($rad['brukernavn']); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
        <br>
        <button type="submit">Slett</button>
    </form>
</body>
</html>
