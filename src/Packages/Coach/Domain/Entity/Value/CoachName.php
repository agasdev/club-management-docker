<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;
use App\Packages\Coach\Domain\Exception\InvalidCoachNameException;

class CoachName extends CommonNotEmptyString
{
    private string $name;
    private string $surname;

    /**
     * @throws InvalidCoachNameException
     */
    public function __construct(string $name, string $surname)
    {
        try {
            $this->name = $name;
            $this->surname = $surname;
            parent::__construct(sprintf('%s %s', $this->name, $this->surname));
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidCoachNameException();
        }
    }

    public function name(): string {
        return $this->name;
    }

    public function surname(): string {
        return $this->surname;
    }
}