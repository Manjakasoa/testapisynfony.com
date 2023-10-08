<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Equipement;
use DateTimeImmutable;

class EquipementTest extends KernelTestCase
{
    public function getEntity() : Equipement {
        $equipement = new Equipement();
        return $equipement
                ->setName('iPhone X 128G')
                ->setCategory('téléphone')
                ->setNumber('1234875')
                ->setDescription('')
                ->setCreatedAt(new \DateTimeImmutable);

    }
    public function assertHasErrors(Equipement $equipement,int $number = 0) {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($equipement);
        $messages = [];
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => '. $error->getMessage();
        }
        $this->assertCount($number,$errors,implode(', ', $messages));
    }

    public function testValidateEntity() {
        $this->assertHasErrors($this->getEntity(),0);
    }
    public function testValidateEmptyNameEntity() {
        $this->assertHasErrors($this->getEntity()->setName(''),1);   
    }
    public function testValidateLengthNameEntity() {
        $this->assertHasErrors($this->getEntity()->setName('Name test greater than 50 characters to validate the length of the equipment'),1); 
    }
    // category must in the array ['téléphone','ordinateur','ecran']
    public function testValidateCategoryEntity() {
        $this->assertHasErrors($this->getEntity()->setCategory('salon'),1);   
    }
    public function testValidateIsNumberNumberEntity() {
        $this->assertHasErrors($this->getEntity()->setNumber('1a34'),1);
    }
    public function testValidateLengthNumberEntity() {
        $this->assertHasErrors($this->getEntity()->setNumber('1234567891012345678921'),1);   
    }
    
}
