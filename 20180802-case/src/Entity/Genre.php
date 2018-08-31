<?php

namespace App\Entity;

//use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre
{
    /**
     * ORM\Id()
     * ORM\GeneratedValue()
     * ORM\Column(type="integer")
     */
    private $id;
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
