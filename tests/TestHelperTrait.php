<?php


namespace App\Tests;


use App\Entity\Parking;
use Faker\Factory;
use Symfony\Component\Validator\ConstraintViolation;

trait TestHelperTrait
{
    public function validateEntity($entity):void
    {
        $this->assertHasErrors($entity, 0);
    }

    public function assertHasErrors($entity, int $number = 0):void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($entity);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function uniqEntityValidation(array $properties):void
    {
        $errors = 0;
        $entity = $this->getEntity();
        foreach($properties as $property => $content) {
            $property = 'set' . $property;
            $entity->{$property}($content);
            $errors++;
        }

        $this->assertHasErrors($entity, $errors);
    }

    public function notBlankProperties(array $properties):void
    {
        foreach ($properties as $property){
            $property = 'set' . $property;
            $entity = $this->getEntity()->{$property}('');

            $this->assertHasErrors($entity, 1);
        }
    }

    public function PropertiesCodePostalValidation(array $properties, string $codePostal, $expected):void
    {
        foreach ($properties as $property){
            $property = 'set' . $property;
            $entity = $this->getEntity()->{$property}($codePostal);

            $this->assertHasErrors($entity, $expected);
        }
    }

    public function PropertiesLengthValidation(array $properties):void
    {
        $faker = Factory::create('fr_FR');

        foreach ($properties as $property => $options){
            $property = 'set' . $property;
            if(isset($options["min"])) {
                $entity = $this->getEntity()->{$property}($faker->lexify(str_repeat('?', $options["min"] - 1)));
                $this->assertHasErrors($entity, 1);
            }
            if(isset($options["max"])) {
                $entity = $this->getEntity()->{$property}($faker->lexify(str_repeat('?', $options["max"] + 1)));
                $this->assertHasErrors($entity, 1);
            }
        }
    }

    public function notBlankRelationships(array $relationships):void
    {
        foreach ($relationships as $relationship){
            $relationship = 'empty' . $relationship;
            $entity = $this->getEntity()->{$relationship}();

            $this->assertHasErrors($entity, 1);
        }
    }

    public function notBlankRelationshipsSideOne(array $relationships):void
    {
        foreach ($relationships as $relationship) {
            $relationship = 'set' . $relationship;
            $entity = $this->getEntity()->{$relationship}(null);

            $this->assertHasErrors($entity, 1);
        }

    }

    public function EmailValidation(array $properties, string $email, $expected):void
    {
        foreach ($properties as $property){
            $property = 'set' . $property;
            $entity = $this->getEntity()->{$property}($email);

            $this->assertHasErrors($entity, $expected);
        }
    }
}