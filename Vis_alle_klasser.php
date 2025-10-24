<?php
include('db-tilkobling.php');
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
