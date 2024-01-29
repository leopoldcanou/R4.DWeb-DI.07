<?php

namespace App\Entity;

class Lego
{
    private $collection;
    private $id;
    private $name;
    private $description;
    private $price;
    private $pieces;
    private $boxImage;
    private $legoImage;

    public function __construct($collection, $id, $name)
    {
        $this->collection = $collection;
        $this->id = $id;
        $this->name = $name;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPieces(): int
    {
        return $this->pieces;
    }

    public function getBoxImage(): string
    {
        return $this->boxImage;
    }

    public function getLegoImage(): string
    {
        return $this->legoImage;
    }



    public function setDescription(string $description): Lego
    {
        $this->description = $description;
        return $this;
    }

    public function setPrice(float $price): Lego
    {
        $this->price = $price;
        return $this;
    }

    public function setPieces(int $pieces): Lego
    {
        $this->pieces = $pieces;
        return $this;
    }

    public function setBoxImage(string $boxImage): Lego
    {
        $this->boxImage = $boxImage;
        return $this;
    }

    public function setLegoImage(string $legoImage): Lego
    {
        $this->legoImage = $legoImage;
        return $this;
    }
    

}