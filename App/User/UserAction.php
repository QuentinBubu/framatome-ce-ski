<?php

namespace App\User;

use Bubu\Database\Database;

use function PHPSTORM_META\type;

class UserAction
{
    public static function reserve(int $id, array $post)
    {
        $allowed = Database::queryBuilder('authorization')
            ->select('reserve')
            ->where(Database::expr()::eq('id', User::getId()))
            ->fetch()['reserve'];

        if (!$allowed) return 'Vous n\'êtes pas autorisé à réserver une sortie';

        $waiting = Database::queryBuilder('sorties')
            ->select('people_waiting', 'temp_available_place')
            ->where(Database::expr()::eq('id', $id))
            ->fetch();

        if ($waiting['temp_available_place'] - (int) $post['worker'] - (int) $post['invite'] < 0) return 'Trop de participants';

        $json = json_decode($waiting['people_waiting'], true);
        if (is_null($json)) $json = [];
        $merge = [User::getId() => [
            'id' => User::getId(),
            'email' => User::getEmail(),
            'username' => User::getUsername(),
            'workers' => $post['worker'],
            'invites' => $post['invite'],
            'withoutInvite' => isset($post['withoutInvite'])
        ]];
        $merge += $json;
        Database::queryBuilder('sorties')
            ->update([
                'people_waiting' => json_encode($merge),
                'temp_available_place' => $waiting['temp_available_place'] - (int) $post['worker'] - (int) $post['invite']
            ])
            ->where(Database::expr()::eq('id', $id))
            ->execute();
        
    }
}
