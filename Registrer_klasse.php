<?php
include('db-tilkobling.php');
$melding = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = trim($_POST['klassekode'] ?? '');
    $klassenavn = trim($_POST['klassenavn'] ?? '');
    $studiumkode = trim($_POST['studiumkode'] ?? '');
    if ($klassekode !== '' && $klassenavn !== '' && $studiumkode !== '') {
        $kode = mysqli_real_escape_string($db, $klassekode);
        $navn = mysqli_real_escape_string($db, $klassenavn);
        $studium = mysqli_real_escape_string($db, $studiumkode);
        // Sjekk om klassekode finnes fra før
        $dupes = mysqli_query($db, "SELECT 1 FROM klasse WHERE klassekode = '$kode'");
        if ($dupes && mysqli_num_rows($dupes) > 0) {
            $melding = 'Klassekode er registrert fra før.';
        } else {
            $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES ('$kode', '$navn', '$studium')";
            if (mysqli_query($db, $sql)) {
                $melding = 'Klasse er registrert.';
            } else {
                $melding = 'Feil: ' . mysqli_error($db);
            }
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
