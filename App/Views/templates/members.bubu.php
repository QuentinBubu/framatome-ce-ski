<?php

use Bubu\Database\Database;

$sorties = Database::queryBuilder('sorties')
    ->select('id', 'name', 'date', 'temp_available_place', 'comments')
    ->where(
        Database::expr()::gt('date', date('Y-m-d'))
    )
    ->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
</head>
<body>
    <table>
        <thead>
            <th>Liste des sorties</th>
            <tr>
                <td>Lieu</td>
                <td>Date</td>
                <td>Place disponibles</td>
                <td>Commentaires</td>
                <td>Reservation</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sorties as $datas): ?>
            <tr>
                <td><?= $datas['name'] ?></td>
                <td><?= date('d/m/Y', strtotime($datas['date'])) ?></td>
                <td><?= $datas['temp_available_place'] ?></td>
                <td><?= $datas['comments'] ?></td>
                <td>
                    <form action="/reserve/<?= $datas['id'] ?>" method="post">
                        <label for="worker<?= $datas['id'] ?>">Nombre d'employés</label>
                        <input type="number" name="worker" id="worker<?= $datas['id'] ?>" min="0" max="<?= $datas['temp_available_place'] ?>">
                        <label for="invite<?= $datas['id'] ?>">Invités</label>
                        <input type="number" name="invite" id="invite<?= $datas['id'] ?>" min="0" max="<?= $datas['temp_available_place'] ?>">
                        <label for="withoutInvite<?= $datas['id'] ?>">Venir sans les invités?</label>
                        <input type="checkbox" name="withoutInvite" id="withoutInvite<?= $datas['id'] ?>">
                        <button>Demander</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>