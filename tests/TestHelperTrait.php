<?php


namespace App\Tests;


use App\Entity\Parking;
use Faker\Factory;
use Symfony\Component\Validator\ConstraintViolation;

trait TestHelperTrait
{
    public function assertHasErrors(Parking $parking, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($parking);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function uniqEntityValidation(array $properties)
    {
        $errors = 0;
        $parking = $this->getEntity();
        foreach($properties as $property => $content) {
            $property = 'set' . $property;
            $parking->{$property}($content);
            $errors++;
        }

        $this->assertHasErrors($parking, $errors);
    }

    public function notBlankProperties(array $properties)
    {
        foreach ($properties as $property){
            $property = 'set' . $property;
            $parking = $this->getEntity()->{$property}('');

            $this->assertHasErrors($parking, 1);
        }
    }

    public function PropertiesCodePostalValidation(array $properties, string $postalCode)
    {
        foreach ($properties as $property){
            $property = 'set' . $property;
            $parking = $this->getEntity()->{$property}($postalCode);

            $this->assertHasErrors($parking, 1);
        }
    }


    public function PropertiesLengthValidation(array $properties)
    {
        $faker = Factory::create('fr_FR');

        foreach ($properties as $property => $options){
            $property = 'set' . $property;
            if(isset($options["min"])) {
                $parking = $this->getEntity()->{$property}($faker->lexify(str_repeat('?', $options["min"] - 1)));
                $this->assertHasErrors($parking, 1);
            }
            if(isset($options["max"])) {
                $parking = $this->getEntity()->{$property}($faker->lexify(str_repeat('?', $options["max"] + 1)));
                $this->assertHasErrors($parking, 1);
            }
        }
    }

    public function notBlankRelationships(array $relationships)
    {
        foreach ($relationships as $relationship){
            $relationship = 'empty' . $relationship;
            $parking = $this->getEntity()->{$relationship}();

            $this->assertHasErrors($parking, 1);
        }
    }
}