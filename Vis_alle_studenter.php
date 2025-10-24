<?php
include('db-tilkobling.php');
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
