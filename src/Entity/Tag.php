<?php
/*
 * Tag Entoty
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Primary kry.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * Films.
     *
     * @var ArrayCollection|Films[] Films
     *
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Films[] Films collection
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    /**
     * Add task to collection.
     *
     * @param Films $film Film entity
     *
     * @return Tag
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
     * @param Films $film Film entity
     */
    public function removeFilm(Films $film): self
    {
        if ($this->films->contains($film)) {
            $this->films->removeElement($film);
            $film->removeTag($this);
        }
    }
}
