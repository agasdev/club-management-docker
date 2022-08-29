<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Coach\Application\Form\Type;

use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Form\Type\CoachFormType;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use Symfony\Component\Form\Test\TypeTestCase;

class CoachFormTypeTest extends TypeTestCase
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

        $coachDto = CoachDto::assemble(CoachFixtures::createFreeCoach());
        $form = $this->factory->create(CoachFormType::class, $coachDto);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['name'], $coachDto->name);
        $this->assertEquals($formData['surname'], $coachDto->surname);
        $this->assertEquals($formData['dateOfBirth'], $coachDto->dateOfBirth);
        $this->assertEquals($formData['city'], $coachDto->city);
        $this->assertEquals($formData['country'], $coachDto->country);
        $this->assertEquals($formData['salary'], $coachDto->salary);
        $this->assertEquals($formData['email'], $coachDto->email);
    }
}
