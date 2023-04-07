<?php

namespace Models;

class Status implements \JsonSerializable
{
    const AVAILABLE = 'Available';
    const SOLD = 'Sold';
    const EXPIRED = 'Expired';

    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function Available(): self
    {
        return new self(self::AVAILABLE);
    }

    public static function Sold(): self
    {
        return new self(self::SOLD);
    }

    public static function Expired(): self
    {
        return new self(self::EXPIRED);
    }


    public function label(): string
    {
        return self::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value->value) {
            self::AVAILABLE => 'Available',
            self::SOLD => 'Sold',
            self::EXPIRED => 'Expired',
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
            self::AVAILABLE => self::Available(),
            self::SOLD => self::Sold(),
            self::EXPIRED => self::Expired(),
            default => throw new InvalidArgumentException("Invalid status value: $value"),
        };
    }
    public function jsonSerialize() :mixed
    {
        return $this->value;
    }


}