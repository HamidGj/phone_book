<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\PhoneNumber;
use App\Models\Group;

class ContactsImport
{
    public function model(array $row)
    {
        $group = Group::firstOrCreate(['name' => $row['group']]);

        $contact = Contact::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'group_id' => $group->id,
        ]);

        foreach (explode(',', $row['phone_numbers']) as $number) {
            PhoneNumber::create([
                'contact_id' => $contact->id,
                'number' => trim($number),
            ]);
        }

        return $contact;
    }
}

