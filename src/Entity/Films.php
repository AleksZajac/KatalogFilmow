<?php
/*
 * Films Entity
 */

namespace App\Entity;

use App\Repository\FilmsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilmsRepository", repositoryClass=FilmsRepository::class)
 */
class Films
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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * Description.
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * Release Date.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Date
     */
    private $releasedate;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="films")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    /**
     * Grtter for Id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter fpr Title.
     *
     * @return Films
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for Description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for ReleaseDate.
     * @return string | array RelaseDate
     */
    public function getReleaseDate(): ?string
    {
        return $this->releasedate;
    }

    /**
     * Setter for ReleaseDate.
     *
     * @return $this
     */
    public function setReleaseDate(string $releasedate): self
    {
        $this->releasedate = $releasedate;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setFilms($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFilms() === $this) {
                $comment->setFilms(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
