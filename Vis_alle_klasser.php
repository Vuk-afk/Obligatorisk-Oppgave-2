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
$sql = "SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode";
$resultat = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Vis alle klasser</title>
</head>
<body>
    <h1>Alle klasser</h1>
    <p><a href="index.php">Tilbake</a></p>
    <?php if ($resultat && mysqli_num_rows($resultat) > 0): ?>
        <table border="1" cellpadding="4" cellspacing="0">
            <tr>
                <th>Klassekode</th>
                <th>Klassenavn</th>
                <th>Studiumkode</th>
            </tr>
            <?php while ($rad = mysqli_fetch_assoc($resultat)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rad['klassekode']); ?></td>
                    <td><?php echo htmlspecialchars($rad['klassenavn']); ?></td>
                    <td><?php echo htmlspecialchars($rad['studiumkode']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Ingen klasser funnet.</p>
    <?php endif; ?>
</body>
</html>
