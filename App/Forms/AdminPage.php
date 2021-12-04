<?php

namespace App\Forms;

use Bubu\Utils\Form\Form;

class AdminPage
{
    public static function waitConfirmSignup(): string
    {
        $newForm = new Form();
        $newForm->action('/admin')
            ->method('post')
            ->add([
                'label' => [
                    $newForm->label->for('name'),
                    $newForm->label->value('Nom de la sortie:')
                ]
            ])
            ->add([
                'input' => [
                    $newForm->input->name('name'),
                    $newForm->input->id('name'),
                    $newForm->input->text,
                    $newForm->input->placeholder('Nom')
                ]
            ])
            ->add([
                'label' => [
                    $newForm->label->for('places'),
                    $newForm->label->value('Nombre de places:')
                ]
            ])
            ->add([
                'input' => [
                    $newForm->input->name('places'),
                    $newForm->input->id('places'),
                    $newForm->input->number,
                    $newForm->input->placeholder('Places')
                ]
            ])
            ->add([
                'label' => [
                    $newForm->label->for('date'),
                    $newForm->label->value('Date de la sortie:')
                ]
            ])
            ->add([
                'input' => [
                    $newForm->input->name('date'),
                    $newForm->input->id('date'),
                    $newForm->input->date,
                    $newForm->input->placeholder('Date')
                ]
            ])
            ->add([
                'label' => [
                    $newForm->label->for('comments'),
                    $newForm->label->value('Commentaires:')
                ]
            ])
            ->add([
                'input' => [
                    $newForm->input->text,
                    $newForm->input->name('comments'),
                    $newForm->input->id('comments')
                ]
            ])
            ->add([
                'input' => [
                    $newForm->input->hidden,
                    $newForm->input->name('form-name'),
                    $newForm->input->value('createSortie')
                ]
            ])
            ->add([
                'button' => [
                    $newForm->button->submit,
                    $newForm->button->value('Enregistrer'),
                    $newForm->button->name('sendForm')
                ]
            ]);
        return $newForm->build();
    }
}
