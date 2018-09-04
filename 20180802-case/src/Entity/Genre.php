<?php

namespace App\Entity;

//use Doctrine\ORM\Mapping as ORM;
//associated books property setup
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

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
