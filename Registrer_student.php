<?php
include('db-tilkobling.php');
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
        // Sjekk om brukernavn finnes fra før
        $dupe = mysqli_query($db, "SELECT 1 FROM student WHERE brukernavn = '$bn'");
        if ($dupe && mysqli_num_rows($dupe) > 0) {
            $melding = 'Brukernavn er registrert fra før.';
        } else {
            // Sjekk om klassekode finnes
            $klasseOK = mysqli_query($db, "SELECT 1 FROM klasse WHERE klassekode = '$kk'");
            if (!$klasseOK || mysqli_num_rows($klasseOK) === 0) {
                $melding = 'Ugyldig klassekode.';
            } else {
                $sql = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES ('$bn', '$fn', '$en', '$kk')";
                if (mysqli_query($db, $sql)) {
                    $melding = 'Student er registrert.';
                } else {
                    $melding = 'Feil: ' . mysqli_error($db);
                }
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
    <title>Registrer student</title>
</head>
<body>
    <h1>Registrer student</h1>
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
