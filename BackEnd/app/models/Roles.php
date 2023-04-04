<?php

namespace Models;

class Roles implements \JsonSerializable
{
    const ADMIN = 'Admin';
    const CUSTOMER = 'Customer';
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function Admin(): self
    {
        return new self(self::ADMIN);
    }

    public static function Customer(): self
    {
        return new self(self::CUSTOMER);
    }

    public function label(): string
    {
        return self::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value->value) {
            self::ADMIN => 'Admin',
            self::CUSTOMER => 'Customer',
            default => throw new InvalidArgumentException("Invalid status value: $value"),
        };
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::ADMIN => self::ADMIN(),
            self::CUSTOMER => self::Customer(),
            default => throw new InvalidArgumentException("Invalid status value: $value"),
        };
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }

}