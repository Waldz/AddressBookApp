<?php

namespace AddressBook\Validator;

use AddressBook\Model\Contact;

/**
 * Converts contact to transient data
 *
 * @package AddressBook
 * @subpackage Validator
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class ContactValidator
{

    /**
     * Transform contact to transient data
     *
     * @param Contact $contact
     * @returns array
     */
    public static function validate(Contact $contact)
    {
        $isEmpty = function ($value) {
            $value = trim($value);
            return empty($value);
        };

        $errors = [];
        if ($isEmpty($contact->getTitle())) {
            $errors['title'] = 'Title required';
        }
        if ($isEmpty($contact->getName())) {
            $errors['name'] = 'Name required';
        }
        if ($isEmpty($contact->getEmail())) {
            $errors['email'] = 'Email required';
        } elseif (filter_var($contact->getEmail(), FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Email is invalid';
        }

        return $errors;
    }

}
