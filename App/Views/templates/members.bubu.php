<?php

use App\User\User;
use Bubu\Database\Database;

$sorties = Database::queryBuilder('sorties')
    ->select('id', 'name', 'date', 'temp_available_place', 'comments')
    ->where(
        Database::expr()::gt('date', date('Y-m-d'))
    )
    ->orderBy('date')
    ->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    +css('members')
    <title>Mon compte</title>
</head>
<body>
    <h2 class="title is-4">Bonjour <?= User::getUsername('username') ?></h2>
    <table class="table is-striped">
        <thead>
            <th colspan="5" class="title is-4 is-underlined">Liste des sorties</th>
            <tr class="has-text-weight-medium is-underlined" class="label">
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
                        <section class="tile is-ancestor">
                            <section class="tile is-parent is-vertical">
                                <label for="worker<?= $datas['id'] ?>" class="label is-underlined">Employés</label>
                                <input type="number" name="worker" id="worker<?= $datas['id'] ?>" min="0" max="<?= $datas['temp_available_place'] ?>" class="input">
                            </section>
                            <section class="tile is-parent is-vertical">
                                <label for="invite<?= $datas['id'] ?>" class="label is-underlined">Invités</label>
                                <input type="number" name="invite" id="invite<?= $datas['id'] ?>" min="0" max="<?= $datas['temp_available_place'] ?>" class="input">
                            </section>
                        </section>
                        <section class="is-expanded">
                            <label for="withoutInvite<?= $datas['id'] ?>" class="checkbox">Venir sans les invités?</label>
                            <input type="checkbox" name="withoutInvite" id="withoutInvite<?= $datas['id'] ?>">
                        </section>
                        <button class="button is-success is-outlined is-fullwidth">Demander</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/logout" class="button is-danger is-light m-4">Logout</a>
</body>
</html>