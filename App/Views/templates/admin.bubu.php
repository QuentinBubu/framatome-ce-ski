<?php

use Bubu\Database\Database;
use App\Forms\AdminPage;

$id = Database::queryBuilder('authorization')
    ->select('id')
    ->where(Database::expr()::eq('access', 0))
    ->fetchAll();

$ids = [];

foreach ($id as $value) array_push($ids, $value['id']);
if (count($ids) !== 0) {
    $ids = Database::queryBuilder('users')
    ->select('id', 'username', 'email')
    ->where(
        Database::expr()::in('id', $ids)
    )
    ->fetchAll();
}

$waitingSorties = Database::queryBuilder('sorties')
    ->select('id', 'name', 'date', 'people_waiting', 'available_place')
    ->where(
        Database::expr()::gt('date', date('Y-m-d'))
    )
    ->orderBy('date')
    ->fetchAll();

$newForm = AdminPage::waitConfirmSignup();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css" />
    <title>Administrateur</title>
</head>
<body>
    <table>
        <thead>
            <th colspan="3">Personne en attente de validation</th>
            <tr>
                <td>Identifiant</td>
                <td>Pseudonyme</td>
                <td>Mail</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ids as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <form action="/admin" method="post">
                            <input type="hidden" name="form-name" value="validAccount">
                            <input type="submit" value="Accepter" name="<?= $user['id'] ?>"/>
                            <input type="submit" value="Refuser" name="<?= $user['id'] ?>"/>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    +|!newForm!|
    <hr>
    <h1>Liste des sorties</h1>
    <hr>
    <?php foreach ($waitingSorties as $sortie): ?>
        <table>
            <thead>
                <tr>
                    <th colspan="5">
                        Sortie du <?= date('d/m/Y', strtotime($sortie['date'])) ?> à <?= $sortie['name'] ?> (<?= $sortie['available_place'] ?> places disponibles)
                    </th>
                </tr>
                <tr>
                    <td>Identifiant</td>
                    <td>Pseudonyme</td>
                    <td>Mail</td>
                    <td>Demande</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) json_decode($sortie['people_waiting'], true) as $people): ?>
                    <tr>
                        <td><?= $people['id'] ?></td>
                        <td><?= $people['username'] ?></td>
                        <td><?= $people['email'] ?></td>
                        <td><?= $people['workers'] ?> employés <br> <?= $people['invites'] ?> invités</td>
                        <td>
                            <form action="/admin" method="post">
                                <input type="hidden" name="form-name" value="validSortiePeople">
                                <input type="hidden" name="sortieId" value="<?= $sortie['id'] ?>">
                                <input type="hidden" name="peopleId" value="<?= $people['id'] ?>">
                                <input type="submit" value="Accepter" name="<?= $people['id'] ?>"/>
                                <input type="submit" value="Refuser" name="<?= $people['id'] ?>"/>
                                <?php if ($people['withoutInvite']): ?>
                                <input type="submit" value="Accepter sans les invités" name="<?= $people['id'] ?>"/>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    <hr>
    <h1>Modifier les permissions d'une personne</h1>
    <form action="/authorizations" method="post">
        <select name="auth-" id=""></select>
    </form>
    <a href="/logout">Logout</a>
</body>
</html>