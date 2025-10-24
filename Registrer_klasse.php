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
