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
    $klassekode = trim($_POST['klassekode'] ?? '');
    $klassenavn = trim($_POST['klassenavn'] ?? '');
    $studiumkode = trim($_POST['studiumkode'] ?? '');
    if ($klassekode !== '' && $klassenavn !== '' && $studiumkode !== '') {
        $kode = mysqli_real_escape_string($db, $klassekode);
        $navn = mysqli_real_escape_string($db, $klassenavn);
        $studium = mysqli_real_escape_string($db, $studiumkode);
        $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES ('$kode', '$navn', '$studium')";
        if (mysqli_query($db, $sql)) {
            $melding = 'Klasse er registrert.';
        } else {
            $melding = 'Feil: ' . mysqli_error($db);
        }
    } else {
        $melding = 'Fyll inn alle felt.';
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Registrer klasse</title>
</head>
<body>
    <h1>Registrer klasse</h1>
    <p><a href="index.php">Tilbake</a></p>
    <?php if ($melding !== ''): ?>
        <p><?php echo htmlspecialchars($melding); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>
            Klassekode
            <input type="text" name="klassekode" maxlength="5" required>
        </label>
        <br>
        <label>
            Klassenavn
            <input type="text" name="klassenavn" maxlength="50" required>
        </label>
        <br>
        <label>
            Studiumkode
            <input type="text" name="studiumkode" maxlength="50" required>
        </label>
        <br>
        <button type="submit">Registrer</button>
    </form>
</body>
</html>
