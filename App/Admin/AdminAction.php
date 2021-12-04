<?php

namespace App\Admin;

use Bubu\Database\Database;
use Bubu\Mail\Mail;

class AdminAction
{
    public static function validAccount(array $post)
    {
        $postFlip = array_flip($post);
        if (array_key_exists('Accepter', $postFlip)) {
            $email = Database::queryBuilder('users')
                ->select('email')
                ->where(Database::expr()::eq('id', $postFlip['Accepter']))
                ->fetch();
            Mail::sendMail($email['email'], 'Framatome CE Ski', 'Votre demande de compte a été acceptée! Vous pouvez désormais vous connecter!');
            Database::queryBuilder('authorization')
                ->update(['access' => 1])
                ->where(Database::expr()::eq('id', $postFlip['Accepter']))
                ->execute();
        } elseif (array_key_exists('Refuser', $postFlip)) {
            $email = Database::queryBuilder('users')
                ->select('email')
                ->where(Database::expr()::eq('id', $postFlip['Refuser']))
                ->fetch();
            Mail::sendMail($email['email'], 'Framatome CE Ski', 'Votre demande de compte a été refusée. Vos données vont être supprimés');
            Database::queryBuilder('authorization')
                ->delete()
                ->where(Database::expr()::eq('id', $postFlip['Refuser']))
                ->execute();
            Database::queryBuilder('users')
                ->delete()
                ->where(Database::expr()::eq('id', $postFlip['Refuser']))
                ->execute();
        }
    }

    public static function createSortie(array $post)
    {
        Database::queryBuilder('sorties')
            ->insert([
                'name' => $post['name'],
                'date' => $post['date'],
                'available_place' => $post['places'],
                'temp_available_place' => $post['places'],
                'comments' => $post['comments']
            ])
            ->execute();
    }

    public static function validSortiePeople(array $post)
    {
        $sortieId = $post['sortieId'];
        $peopleId = $post['peopleId'];
        $postFlip = array_flip($post);
        $waitingSorties = Database::queryBuilder('sorties')
            ->select('date', 'people_waiting', 'available_place', 'participant', 'temp_available_place')
            ->where(Database::expr()::eq('id', $sortieId))
            ->fetch();
        $peopleWaiting = json_decode($waitingSorties['people_waiting'], true);
        $people = $peopleWaiting[$peopleId];
        unset($peopleWaiting[$peopleId]);

        $email = $people['email'];
        $date = date('d/m/Y', strtotime($waitingSorties['date']));
        if (array_key_exists('Accepter', $postFlip)) {
            $participant = json_decode($waitingSorties['participant'], true);
            $participant[] = $peopleId;
            $availablePlace = $waitingSorties['available_place'] - (int) $people['workers'] - (int) $people['invites'];
            Database::queryBuilder('sorties')
                ->update([
                    'available_place' => $availablePlace,
                    'participant' => json_encode($participant),
                    'people_waiting' => json_encode($peopleWaiting)
                ])
                ->where(Database::expr()::eq('id', $sortieId))
                ->execute();

            Mail::sendMail($email, 'Sortie du ' . $date, <<<HTML
                <p>Vous êtes accepté pour votre sortie du $date (ainsi que vos invités).</p>
            HTML);
        } elseif (array_key_exists('Refuser', $postFlip)) {
            $availablePlace = $waitingSorties['temp_available_place'] + (int) $people['workers'] + (int) $people['invites'];
            Database::queryBuilder('sorties')
                ->update([
                    'temp_available_place' => $availablePlace,
                    'people_waiting' => json_encode($peopleWaiting)
                ])
                ->where(Database::expr()::eq('id', $sortieId))
                ->execute();
            Mail::sendMail($email, 'Sortie du ' . $date, <<<HTML
                <p>Votre réservation a été refusée pour la sortie du $date.</p>
            HTML);
        } elseif (array_key_exists('Accepter sans les invités', $postFlip)) {
            $participant = json_decode($waitingSorties['participant'], true);
            $participant[] = $peopleId;
            $availablePlace = $waitingSorties['available_place'] - (int) $people['workers'];
            $tmpAvailablePlace = $waitingSorties['temp_available_place'] + (int) $people['invites'];
            Database::queryBuilder('sorties')
                ->update([
                    'temp_available_place' => $tmpAvailablePlace,
                    'available_place' => $availablePlace,
                    'participant' => json_encode($participant),
                    'people_waiting' => json_encode($peopleWaiting)
                ])
                ->where(Database::expr()::eq('id', $sortieId))
                ->execute();

            Mail::sendMail($email, 'Sortie du ' . $date, <<<HTML
                <p>Vous êtes accepté pour votre sortie du $date mais vos invités ont été refusé.</p>
            HTML);
        }
    }
}
