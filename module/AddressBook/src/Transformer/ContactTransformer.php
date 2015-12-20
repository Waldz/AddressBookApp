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
        $supervisor = $contact->getSupervisor();

        return [
            'id' => $contact->getId(),
            'title' => $contact->getTitle(),
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'supervisor' => $supervisor ? self::toTransient($supervisor) : null,
            'supervised_contacts' => self::listToTransient($contact->getSupervisedContacts())
        ];
    }

    /**
     * Transform contact list to transient data
     *
     * @param Contact[] $contacts
     * @returns array
     */
    public static function listToTransient($contacts)
    {
        return array_map(
            function (Contact $contact) {
                return self::toTransient($contact);
            },
            $contacts
        );
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
        $contactGetOrNull = function ($param) use($requestData) {
            return isset($requestData[$param]) ? $requestData[$param] : null;
        };
        $contact
            ->setTitle($contactGetOrNull('title'))
            ->setName($contactGetOrNull('name'))
            ->setEmail($contactGetOrNull('email'));


        $supervisorId = null;
        $supervisorJson = $contactGetOrNull('supervisor');
        if (is_array($supervisorJson) && isset($supervisorJson['id'])) {
            $supervisorId = $supervisorJson['id'];
        }
        $contact->setSupervisorId($supervisorId);

        return $contact;
    }

}
