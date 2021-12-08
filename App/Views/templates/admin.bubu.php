<?php

use App\Forms\AdminPage;
use Bubu\Database\Database;

$id = Database::queryBuilder('authorization')
    ->select('id')
    ->where(Database::expr()::eq('access', 0))
    ->fetchAll();

$ids = [];

foreach ($id as $value) {
    array_push($ids, $value['id']);
}

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
    +css('admin')
    <title>Administrateur</title>
</head>
<body>
    <table class="table is-striped">
        <thead>
            <th colspan="4" class="title is-4 is-underlined">Personne en attente de validation</th>
            <tr class="has-text-weight-medium">
                <td>Identifiant</td>
                <td>Pseudonyme</td>
                <td>Mail</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ids as $user) : ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <form action="/admin" method="post">
                            <input type="hidden" name="form-name" value="validAccount">
                            <input type="submit" value="Accepter" class="button is-primary is-outlined" name="<?= $user['id'] ?>" />
                            <input type="submit" value="Refuser" class="button is-danger is-outlined" name="<?= $user['id'] ?>" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <form action="/admin" method="post">
        <label for="name" class="label">Nom de la sortie:</label>
        <input name="name" class="input" id="name" type="text" placeholder="Nom" required>
        <p class="help is-danger mb-2">Champs requis.</p>

        <label for="places" class="label">Nombre de places:</label>
        <input name="places" class="input" id="places" type="number" placeholder="Places" required>
        <p class="help is-danger mb-2">Champs requis.</p>

        <label for="date" class="label">Date de la sortie:</label>
        <input name="date" class="input" id="date" type="date" placeholder="Date" required>
        <p class="help is-danger mb-2">Champs requis.</p>

        <label for="comments" class="label">Commentaires:</label>
        <textarea name="comments" id="comments" class="textarea"></textarea>

        <input type="hidden" name="form-name" value="createSortie">
        <button type="submit" class="button is-primary is-outlined" name="sendForm">Enregistrer</button>
    </form>
    <hr>
    <h2 class="title is-4 is-underlined">Liste des sorties</h1>
    <hr>
    <?php foreach ($waitingSorties as $sortie) : ?>
        <table class="table is-striped">
            <thead>
                <tr class="title is-5 is-underlined">
                    <th colspan="5">
                        Sortie du <?= date('d/m/Y', strtotime($sortie['date'])) ?> à <?= $sortie['name'] ?> (<?= $sortie['available_place'] ?> places disponibles)
                    </th>
                </tr>
                <tr class="has-text-weight-medium">
                    <td>Identifiant</td>
                    <td>Pseudonyme</td>
                    <td>Mail</td>
                    <td>Demande</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) json_decode($sortie['people_waiting'], true) as $people) : ?>
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
                                <input type="submit" value="Accepter" class="button is-primary" name="<?= $people['id'] ?>" />
                                <input type="submit" value="Refuser" class="button is-danger" name="<?= $people['id'] ?>" />
                                <?php if ($people['withoutInvite']) : ?>
                                    <input type="submit" value="Accepter sans les invités" class="button is-warning" name="<?= $people['id'] ?>" />
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
    <a href="/logout" class="button is-danger is-light m-4">Logout</a>
    <a href="/members" class="button is-info is-light m-4">Espace membre</a>
</body>
</html>