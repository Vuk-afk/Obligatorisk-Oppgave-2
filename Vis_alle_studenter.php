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
$sql = "SELECT student.brukernavn, student.fornavn, student.etternavn, student.klassekode, klasse.klassenavn FROM student JOIN klasse ON student.klassekode = klasse.klassekode ORDER BY student.brukernavn";
$resultat = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Vis alle studenter</title>
</head>
<body>
    <h1>Alle studenter</h1>
    <p><a href="index.php">Tilbake</a></p>
    <?php if ($resultat && mysqli_num_rows($resultat) > 0): ?>
        <table border="1" cellpadding="4" cellspacing="0">
            <tr>
                <th>Brukernavn</th>
                <th>Fornavn</th>
                <th>Etternavn</th>
                <th>Klassekode</th>
                <th>Klassenavn</th>
            </tr>
            <?php while ($rad = mysqli_fetch_assoc($resultat)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rad['brukernavn']); ?></td>
                    <td><?php echo htmlspecialchars($rad['fornavn']); ?></td>
                    <td><?php echo htmlspecialchars($rad['etternavn']); ?></td>
                    <td><?php echo htmlspecialchars($rad['klassekode']); ?></td>
                    <td><?php echo htmlspecialchars($rad['klassenavn']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Ingen studenter funnet.</p>
    <?php endif; ?>
</body>
</html>