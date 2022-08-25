<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;
use App\Packages\Player\Domain\Exception\InvalidPlayerNameException;

class PlayerName extends CommonNotEmptyString
{
    private string $name;
    private string $surname;

    /**
     * @throws InvalidPlayerNameException
     */
    public function __construct(string $name, string $surname)
    {
        if (empty($name)) {
            throw new InvalidPlayerNameException();
        }

        if (empty($surname)) {
            throw new InvalidPlayerNameException();
        }

        try {
            $this->name = $name;
            $this->surname = $surname;
            parent::__construct(sprintf('%s %s', $this->name, $this->surname));
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidPlayerNameException();
        }
    }

    public function name(): string {
        return $this->name;
    }

    public function surname(): string {
        return $this->surname;
    }
}