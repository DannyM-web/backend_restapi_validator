<?php

namespace Acme\Services;

use Acme\Database\Models\Lead;
use Acme\Database\Models\Contact;
use Acme\Database\Models\Phone;
use Acme\Database\Models\ContactPhone;
use Psr\Http\Message\RequestInterface as Request;

class StoreService
{

    private $lead;
    private $contact;
    private $phone;
    private $contactPhone;
    public function __construct(Contact $contact,  Phone $phone, ContactPhone $contactPhone, Lead $lead)
    {
        $this->lead = $lead;
        $this->contact = $contact;
        $this->phone = $phone;
        $this->contactPhone = $contactPhone;
    }

    public function storeLead($data)
    {
        $path_fmt = explode('lead/', $_SERVER['REQUEST_URI'])[1];

        if ($data) {

            if ($data['contact']) {
                if (
                    $data['contact']['name'] &&
                    $data['contact']['surname'] &&
                    $data['contact']['email']
                ) {
                    $this->contact->create($data['contact']);
                    $created_contact_id = $this->contact->getLastId();
                    // var_dump('created contact[id] ' . $created_contact_id) . PHP_EOL;
                }

                if ($data['contact']['phone']) {
                    $this->phone->create($data['contact']);
                    $created_phone_id = $this->phone->getLastId();
                    // var_dump('created phone[id] ' . $created_phone_id) . PHP_EOL;
                }

                if ($created_phone_id && $created_contact_id) {
                    $contact_phone_data = [
                        'contact_id' => (int)$created_contact_id,
                        'phone_id' =>  (int)$created_phone_id
                    ];
                    $this->contactPhone->create($contact_phone_data);
                    $created_contact_phone_id = $this->contactPhone->getLastId();
                    // var_dump('Association contact_phone ID ' . $created_contact_phone_id);
                }

                $property_id = ($data['contact']['property_id']) ? $data['contact']['property_id'] : null;
            }

            $evaluate_obj = array();
            $evaluate_obj['contact_id'] = $created_contact_id;
            $evaluate_obj['contact_phone_id'] = $created_contact_phone_id;
            $evaluate_obj['property_id'] = (int)$property_id;
            $evaluate_obj['type'] = $path_fmt;

            if ($path_fmt === 'evaluate') {
                if ($data['evaluate']) {
                    foreach ($data['evaluate'] as $key => $value) {
                        $evaluate_obj[$key] = $value;
                    }
                }
            }
            $this->lead->create($evaluate_obj);
            $created_lead_id = $this->lead->getLastId();
            // var_dump('created lead ID ' . $created_lead_id);
        }

        return $created_lead_id;
    }
}
