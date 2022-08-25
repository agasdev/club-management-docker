<?php

declare(strict_types=1);

namespace App\Packages\Common\Domain\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;
use LogicException;
use Symfony\Component\Uid\Uuid as UuidLibrary;

class CommonUuid
{
    protected string $value;

    /**
     * @throws InvalidCommonUuidException
     */
    public function __construct(string $value) {
        $this->validate($value);

        $this->value = $value;
    }

    public function value(): string {
        return $this->value;
    }

    /**
     * @param $id
     *
     * @throws InvalidCommonUuidException
     */
    private function validate($id): void {
        if (!UuidLibrary::isValid($id)) {
            throw new InvalidCommonUuidException($id);
        }
    }

    /**
     * @return $this
     */
    public static function new(): self {
        try {
            return new static(UuidLibrary::v4()->toRfc4122());
        }
        catch (InvalidCommonUuidException $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function __toString(): string {
        return $this->value();
    }
}