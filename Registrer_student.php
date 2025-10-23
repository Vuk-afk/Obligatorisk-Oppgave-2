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
mysqli_query($db, "CREATE TABLE IF NOT EXISTS klasse (klassekode CHAR(5) NOT NULL, klassenavn VARCHAR(50) NOT NULL, studiumkode VARCHAR(50) NOT NULL, PRIMARY KEY (klassekode))");
mysqli_query($db, "CREATE TABLE IF NOT EXISTS student (brukernavn CHAR(7) NOT NULL, fornavn VARCHAR(50) NOT NULL, etternavn VARCHAR(50) NOT NULL, klassekode CHAR(5) NOT NULL, PRIMARY KEY (brukernavn), FOREIGN KEY (klassekode) REFERENCES klasse (klassekode))");
$melding = '';
$klasser = mysqli_query($db, "SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = trim($_POST['brukernavn'] ?? '');
    $fornavn = trim($_POST['fornavn'] ?? '');
    $etternavn = trim($_POST['etternavn'] ?? '');
    $klassekode = $_POST['klassekode'] ?? '';
    if ($brukernavn !== '' && $fornavn !== '' && $etternavn !== '' && $klassekode !== '') {
        $bn = mysqli_real_escape_string($db, strtolower($brukernavn));
        $fn = mysqli_real_escape_string($db, $fornavn);
        $en = mysqli_real_escape_string($db, $etternavn);
        $kk = mysqli_real_escape_string($db, $klassekode);
        $sql = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES ('$bn', '$fn', '$en', '$kk')";
        if (mysqli_query($db, $sql)) {
            $melding = 'Student er registrert.';
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
    <title>Registrer student</title>
</head>
<body>
    <h1>Registrer student</h1>
    <p><a href="index.php">Tilbake</a></p>
    <?php if ($melding !== ''): ?>
        <p><?php echo htmlspecialchars($melding); ?></p>
    <?php endif; ?>
    <?php if ($klasser && mysqli_num_rows($klasser) > 0): ?>
        <form method="post" action="">
            <label>
                Brukernavn
                <input type="text" name="brukernavn" maxlength="7" required>
            </label>
            <br>
            <label>
                Fornavn
                <input type="text" name="fornavn" maxlength="50" required>
            </label>
            <br>
            <label>
                Etternavn
                <input type="text" name="etternavn" maxlength="50" required>
            </label>
            <br>
            <label for="klassekode">Klassekode</label>
            <select name="klassekode" id="klassekode" required>
                <option value="">-- Velg klasse --</option>
                <?php while ($rad = mysqli_fetch_assoc($klasser)): ?>
                    <option value="<?php echo htmlspecialchars($rad['klassekode']); ?>">
                        <?php echo htmlspecialchars($rad['klassekode'] . ' - ' . $rad['klassenavn']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <br>
            <button type="submit">Registrer</button>
        </form>
    <?php else: ?>
        <p>Registrer en klasse før du legger til studenter.</p>
    <?php endif; ?>
</body>
</html>