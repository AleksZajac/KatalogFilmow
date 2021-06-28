<?php
/*
 * FavoriteMovies Entity
 */

namespace App\Entity;

use App\Repository\FavoriteMoviesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FAvoriteMovies claass.
 *
 * @ORM\Table(name="favorite_movies")
 * @ORM\Entity(repositoryClass=FavoriteMoviesRepository::class)
 */
class FavoriteMovies
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
     * @ORM\ManyToMany(targetEntity=Films::class, inversedBy="favoriteMovies")
     */
    private $film;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="favoriteMovies", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * FavoriteMovies constructor.
     */
    public function __construct()
    {
        $this->film = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Films[]
     */
    public function getFilm(): Collection
    {
        return $this->film;
    }

    /**
     * @param Films $film
     *
     * @return $this
     */
    public function addFilm(Films $film): self
    {
        if (!$this->film->contains($film)) {
            $this->film[] = $film;
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
        $this->film->removeElement($film);

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
