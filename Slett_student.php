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
mysqli_set_charset($db, 'utf8mb4');
function tableExists($db, $name) {
    $name = mysqli_real_escape_string($db, $name);
    $res = mysqli_query($db, "SHOW TABLES LIKE '$name'");
    return $res && mysqli_num_rows($res) > 0;
}
if (!tableExists($db, 'klasse') || !tableExists($db, 'student')) {
    $schemaFile = __DIR__ . '/database.php';
    if (is_file($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        if ($sql !== false && $sql !== '') {
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if ($statement === '') continue;
                @mysqli_query($db, $statement);
            }
        }
    }
}
mysqli_query($db, "CREATE TABLE IF NOT EXISTS klasse (klassekode CHAR(5) NOT NULL, klassenavn VARCHAR(50) NOT NULL, studiumkode VARCHAR(50) NOT NULL, PRIMARY KEY (klassekode))");
mysqli_query($db, "CREATE TABLE IF NOT EXISTS student (brukernavn CHAR(7) NOT NULL, fornavn VARCHAR(50) NOT NULL, etternavn VARCHAR(50) NOT NULL, klassekode CHAR(5) NOT NULL, PRIMARY KEY (brukernavn), FOREIGN KEY (klassekode) REFERENCES klasse (klassekode))");
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
