<?php

namespace AddressBook\Transformer;

use AddressBook\Model\Contact;

/**
 * Converts contact to transient data
 *
 * @package AddressBook
 * @subpackage Transformer
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class ContactTransformer
{

    /**
     * Transform contact to transient data
     *
     * @param Contact $contact
     * @returns array
     */
    public static function toTransient(Contact $contact)
    {
        return [
            'id' => $contact->getId(),
            'title' => $contact->getTitle(),
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'supervisor_id' => $contact->getSupervisorId()
        ];
    }

    /**
     * Transform request to contact.
     *
     * @param array $requestData
     * @param Contact $contact
     *
     * @return Contact
     */
    public static function fromRequest(array $requestData, $contact)
    {
        $getOrNull = function ($param) use($requestData) {
            return isset($requestData[$param]) ? $requestData[$param] : null;
        };

        $contact
            ->setTitle($getOrNull('title'))
            ->setName($getOrNull('name'))
            ->setEmail($getOrNull('email'))
            ->setSupervisorId($getOrNull('supervisor_id'));

        return $contact;
    }

}
