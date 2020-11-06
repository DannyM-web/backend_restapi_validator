<?php

namespace Acme\Controller;

use Acme\Exception\Validation\EmptyFieldException;
use Acme\Exception\Validation\InvalidFieldException;

use Exception;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class DataController
{
    private $data;
    private $path;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function action(Request $request, Response $response)
    {

        $this->data = $request->getParsedBody();
        $this->path  = $request->getUri()->getPath();

        $this->validateFields();

        $result = $this->retrieveLeadId();


        if (!$result) {
            return $response->withStatus(400);
        }

        return $response->withJson([
            'lead_id' => $result
        ]);
    }

    private function retrieveLeadId()
    {

        $storeService = $this->container->get('service.store');

        $result = $storeService->storeLead($this->data);

        return $result;
    }


    private function validateFields()
    {
        $path_fmt = explode('lead/', $this->path)[1];

        $this->validateNameSurname();
        $this->validateEmail();
        $this->validatePhone();
        if ($path_fmt === 'evaluate') {
            $this->validateTypology();
            $this->validateSurface();
            $this->validateFloor();
            $this->validateCondition();
            $this->validateAddress();
            $this->validateLatLon();
        }
        if ($path_fmt === 'request') {
            $this->validatePropertyId();
        }
    }

    private function validateNameSurname()
    {
        $name = trim($this->data['contact']['name']);
        $surname = trim($this->data['contact']['surname']);

        if (empty($name) || empty($surname)) {
            throw new EmptyFieldException('name or surname');
        } else {

            if (!preg_match("/^([a-zA-Z' ]+)$/", $name)) {
                throw new InvalidFieldException('name');
            }

            if (!preg_match("/^([a-zA-Z' ]+)$/", $surname)) {
                throw new InvalidFieldException('surname');
            }
        }

        return true;
    }

    private function validateEmail()
    {
        $email = trim($this->data['contact']['email']);

        if (!$this->checkIsNotEmpty($email)) {
            throw new EmptyFieldException('email');
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidFieldException('email');
            }
        }

        return true;
    }

    private function validatePhone()
    {
        $phone = trim($this->data['contact']['phone']);

        if (!$this->checkIsNotEmpty($phone)) {
            throw new EmptyFieldException('phone');
        } else {
            if (!preg_match('/^3\d{9}$/', $phone)) {
                throw new InvalidFieldException('phone');
            }
        }

        return true;
    }

    private function validatePropertyId()
    {
        $val = $this->data['contact']['property_id'];

        if (!$this->checkIsNotEmpty($val)) {
            throw new EmptyFieldException('property_id');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_INT,  array("options" => array("min_range" => 1)))) {
                throw new InvalidFieldException('property_id');
            }
        }
    }

    private function validateTypology()
    {

        $val = ucfirst($this->data['evaluate']['typology']);
        $acceptedValues = ['Appartamento', 'Villa', 'Casale', 'Monolocale'];

        if (!$this->checkIsNotEmpty(trim($val))) {
            throw new EmptyFieldException('typology');
        } else {
            if (!in_array($val, $acceptedValues)) {
                throw new InvalidFieldException('typology');
            }
        }


        return true;
    }

    private function validateSurface()
    {

        $val = $this->data['evaluate']['surface'];

        if (!$this->checkIsNotEmpty($val)) {
            throw new EmptyFieldException('surface');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_INT,  array("options" => array("min_range" => 1)))) {
                throw new InvalidFieldException('surface');
            }
        }



        return true;
    }

    private function validateFloor()
    {
        $val = $this->data['evaluate']['floor'];
        $min = -1;
        $max = 10;
        if (!$this->checkIsNotEmpty($val)) {
            throw new EmptyFieldException('floor');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_INT,  array("options" => array("min_range" => $min, "max_range" => $max)))) {
                throw new InvalidFieldException('floor');
            }
        }



        return true;
    }

    private function validateCondition()
    {
        $val = ucfirst($this->data['evaluate']['condition']);
        $acceptedValues = ['Ottimo stato', 'Buono stato', 'Da ristrutturare'];

        if (!$this->checkIsNotEmpty(trim($val))) {
            throw new EmptyFieldException('condition');
        } else {
            if (!in_array($val, $acceptedValues)) {
                throw new InvalidFieldException('condition');
            }
        }

        return true;
    }

    private function validateAddress()
    {
        $val = $this->data['evaluate']['address'];
        if (!$this->checkIsNotEmpty(trim($val))) {
            throw new EmptyFieldException('address');
        } else {
            if (!preg_match('^[a-zA-Z0-9,.!? ]*$^', $val)) {
                throw new InvalidFieldException('address');
            }
        }

        return true;
    }

    private function validateLatLon()
    {
        $lat = $this->data['evaluate']['latitude'];
        $lon = $this->data['evaluate']['longitude'];

        if (!$this->checkIsNotEmpty($lat)) {
            throw new EmptyFieldException(('latitude'));
        } else {
            if (filter_var($lat, FILTER_VALIDATE_FLOAT)) {
                if (!preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat)) {
                    throw new InvalidFieldException('latitude');
                }
            } else {
                throw new InvalidFieldException('latitude');
            }
        }

        if (!$this->checkIsNotEmpty($lon)) {
            throw new EmptyFieldException('longitude');
        } else {
            if (filter_var($lon, FILTER_VALIDATE_FLOAT)) {
                if (!preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lon)) {
                    throw new InvalidFieldException('latitude');
                }
            } else {
                throw new InvalidFieldException('latitude');
            }
        }

        return true;
    }


    private function checkIsNotEmpty($field)
    {
        if (empty($field)) {
            return false;
        }

        return true;
    }
}
