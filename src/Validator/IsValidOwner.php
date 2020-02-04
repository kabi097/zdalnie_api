<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidOwner extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Błąd. Niepoprawny użytkownik.';

    public $anonymousMessage = 'Niezalogowany użytkownik. Nie można dodać obiektu';
}
