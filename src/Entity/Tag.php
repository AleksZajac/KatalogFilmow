<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"name"})
 */
class Tag
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-optionsda.
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Films[] Films
     * @ORM\ManyToMany(targetEntity=Films::class, mappedBy="tags")
     */
    private $films;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->films = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Films[] Films collection
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    /**
     * Add task to collection.
     *
     * @param \App\Entity\Films $film Film entity
     */
    public function addFilm(Films $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->addTag($this);
        }

        return $this;
    }

    /**
     * Remove task from collection.
     *
     * @param \App\Entity\Films $film Film entity
     */
    public function removeFilm(Films $film): self
    {
        if ($this->films->contains($film)) {
            $this->films->removeElement($film);
            $film->removeTag($this);
        }
    }
}
