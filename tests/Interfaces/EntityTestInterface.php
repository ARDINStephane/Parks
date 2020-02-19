<?php


namespace App\Tests\Interfaces;

    /**
     * Interface EntityTestInterface
     * @package App\Tests\Interfaces
     *
     * use TestHelperTrait;
     */
    interface EntityTestInterface
    {
        public function getEntity();

        public function testValidEntity(): void;
    }
