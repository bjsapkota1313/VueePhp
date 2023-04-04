<?php

namespace Models;
use DateTime;
use Models\User;
use Models\Status;


class Ad implements \JsonSerializable
{
    private int $id;
    private string $productName;
    private string $description;
    private DateTime $postedDate;
    private float $price;
    private string $imageUri;
    private User $user;
    private Status $status;

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getImageUri(): string
    {
        return $this->imageUri;
    }

    /**
     * @param string $imageUri
     */
    public function setImageUri(string $imageUri): void
    {
        $this->imageUri = $imageUri;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return String
     */
    public function getPostedDate(): string
    {
        return $this->postedDate->format('Y-m-d');
    }

    /**
     * @param String $postedDate
     */
    public function setPostedDate(DateTime $postedDate): void
    {
        $this->postedDate = $postedDate;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
    public function __equals($other): bool
    {
        return $this->id === $other->id;
    }

}