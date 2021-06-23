<?php

namespace App\Entity;

use App\Repository\FavoriteMoviesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\ManyToMany(targetEntity=Films::class)
     * @Assert\Type(type="Doctrine\Common\Collections\Collection")
     *

     */
    private $id_film;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     *
     */
    private $id_user;

    public function __construct()
    {
        $this->id_film = new ArrayCollection();
        $this->id_user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Films[]
     */
    public function getIdFilm(): Collection
    {
        return $this->id_film;
    }

    /**
     * @param Films $idFilm
     */
    public function addIdFilm(Films $idFilm): self
    {
        if (!$this->id_film->contains($idFilm)) {
            $this->id_film[] = $idFilm;
        }

        return $this;
    }

    public function removeIdFilm(Films $idFilm)
    {
        $this->id_film->removeElement($idFilm);

        return $this;
    }


    public function getIdUser()
    {
        return $this->id_user;
    }


    public function setIdUser(?User $id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }
}
