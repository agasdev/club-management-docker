<?php

namespace App\Tests\Packages\Club\Application\Form\Type;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Form\Type\ClubFormType;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use Symfony\Component\Form\Test\TypeTestCase;

class ClubFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Name',
            'city' => 'City',
            'country' => 'Country',
            'budget' => 1000000,
        ];

        $clubDto = ClubDto::assemble(ClubFixtures::create());
        $form = $this->factory->create(ClubFormType::class, $clubDto);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['name'], $clubDto->name);
        $this->assertEquals($formData['city'], $clubDto->city);
        $this->assertEquals($formData['country'], $clubDto->country);
        $this->assertEquals($formData['budget'], $clubDto->budget);
    }
}
