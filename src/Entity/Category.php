<?php
/*
 * Category Entity
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository", repositoryClass=CategoryRepository::class)
 */
class Category
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
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Type (type="string")
     * @Assert\NotBlank
     * @Assert\Length (
     *     allowEmptyString=false,
     *     min="3",
     *     max="32"
     *     )
     */
    private $name;

    /**
     * @var ArrayCollection|Films[] Films
     *
     * @ORM\OneToMany(targetEntity=Films::class, mappedBy="category")
     */
    private $films;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->films = new ArrayCollection();
    }

    /**
     * Getter for Id.
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name.
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
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
     * @return Collection|Films[]
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    /**
     * @param Films $film Film Entity
     *
     * @return $this
     */
    public function addFilm(Films $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Films $film
     *
     * @return $this
     */
    public function removeFilm(Films $film): self
    {
        if ($this->films->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getCategory() === $this) {
                $film->setCategory(null);
            }
        }

        return $this;
    }
}
