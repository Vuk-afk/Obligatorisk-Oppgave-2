<?php
mysqli_report(MYSQLI_REPORT_OFF);
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
if ($password === false) {
    $password = getenv('DB_PASSWORD');
}
$database = getenv('DB_NAME');
if ($database === false) {
    $database = getenv('DB_DATABASE');
}
$db = mysqli_connect($host, $username, $password, $database) or die('ikke kontakt med database-server');
$melding = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = $_POST['brukernavn'] ?? '';
    if ($brukernavn !== '') {
        $bn = mysqli_real_escape_string($db, $brukernavn);
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
</head>
<body>
    <h1>Slett student</h1>
    <p><a href="index.php">Tilbake</a></p>
    <?php if ($melding !== ''): ?>
        <p><?php echo htmlspecialchars($melding); ?></p>
    <?php endif; ?>
    <form method="post" action="">
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