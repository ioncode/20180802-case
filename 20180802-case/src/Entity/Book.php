<?php

namespace App\Entity;

//use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * ORM\Id()
     * ORM\GeneratedValue()
     * ORM\Column(type="integer")
     */
    private $id;
    private $title;
    private $release_date;
    private $catalog_date;
    private $rate;
    private $genre;
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getReleaseDate()
    {
        return $this->release_date;
    }

    /**
     * @param mixed $release_date
     */
    public function setReleaseDate($release_date): void
    {
        $this->release_date = $release_date;
    }

    /**
     * @return mixed
     */
    public function getCatalogDate()
    {
        return $this->catalog_date;
    }


    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }
}
