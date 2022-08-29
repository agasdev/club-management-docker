<?php

namespace App\Tests\Packages\Player\Application\Form\Type;

use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Form\Type\PlayerFormType;
use App\Packages\Player\Domain\Entity\Player;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use Symfony\Component\Form\Test\TypeTestCase;

class PlayerFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Name',
            'surname' => 'Surame',
            'dateOfBirth' => '1991-10-23',
            'city' => 'City',
            'country' => 'Country',
            'salary' => 30000,
            'email' => 'coach@email.email',
        ];

        $playerDto = PlayerDto::assemble(PlayerFixtures::createFreePlayer());
        $form = $this->factory->create(PlayerFormType::class, $playerDto);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['name'], $playerDto->name);
        $this->assertEquals($formData['surname'], $playerDto->surname);
        $this->assertEquals($formData['dateOfBirth'], $playerDto->dateOfBirth);
        $this->assertEquals($formData['city'], $playerDto->city);
        $this->assertEquals($formData['country'], $playerDto->country);
        $this->assertEquals($formData['salary'], $playerDto->salary);
        $this->assertEquals($formData['email'], $playerDto->email);
    }
}
