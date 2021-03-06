<?php

namespace TinyRest\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use TinyRest\Annotations\Property;
use TinyRest\Exception\ValidationException;
use TinyRest\TransferObject\TransferObjectInterface;

class RequestHandlerTest extends RequestHandlerCase
{
    public function testValidation()
    {
        $request        = Request::create('localhost', 'GET', ['firstName' => '']);
        $requestHandler = $this->createRequestHandler();
        $transferObject = $this->getTransferObject();

        $this->expectException(ValidationException::class);
        $requestHandler->handleTransferObject($request, $transferObject);
    }

    public function testValidationGroups()
    {
        $request        = Request::create('localhost', 'GET', ['firstName' => 'John']);
        $requestHandler = $this->createRequestHandler();
        $transferObject = $this->getTransferObject();

        $requestHandler->setValidationGroups(['GroupB']);
        $this->expectException(ValidationException::class);
        $requestHandler->handleTransferObject($request, $transferObject);
    }

    private function getTransferObject() : TransferObjectInterface
    {
        return new class implements TransferObjectInterface
        {
            /**
             * @Property()
             * @Assert\NotBlank(groups={"Default", "GroupA"})
             */
            public $firstName;

            /**
             * @Property()
             * @Assert\NotBlank(groups={"GroupB"})
             */
            public $lastName;
        };
    }
}
