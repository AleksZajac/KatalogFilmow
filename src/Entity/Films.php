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
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="films", cascade={"persist","remove"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="films")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * Tags
     * @var array
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="films", fetch="EXTRA_LAZY",)
     * @ORM\JoinTable(name="films_tags")
     * @Assert\Type(type="Doctrine\Common\Collections\Collection")
     */
    private $tags;

    /**
     * @ORM\OneToOne(targetEntity=Photo::class, mappedBy="films", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity=FavoriteMovies::class, mappedBy="id_film", fetch="EXTRA_LAZY")
     */
    private $favoriteMovies;



    /**
     * Film constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->favoriteMovies = new ArrayCollection();
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

    /**
     * Getter for tags.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Tag[] Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Remove tag from collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    public function getFileName(): ?Photo
    {
        return $this->fileName;
    }

    public function setFileName(Photo $fileName): self
    {
        // set the owning side of the relation if necessary
        if ($fileName->getFilms() !== $this) {
            $fileName->setFilms($this);
        }

        $this->fileName = $fileName;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(Photo $photo): self
    {
        // set the owning side of the relation if necessary
        if ($photo->getFilms() !== $this) {
            $photo->setFilms($this);
        }

        $this->photo = $photo;

        return $this;
    }

    /**
     * @return \App\Entity\FavoriteMovies
     */
    public function getFavoriteMovies(): Collection
    {
        return $this->favoriteMovies;
    }

    public function addFavoriteMovie(FavoriteMovies $favoriteMovie): self
    {
        if (!$this->favoriteMovies->contains($favoriteMovie)) {
            $this->favoriteMovies[] = $favoriteMovie;
            $favoriteMovie->addIdFilm($this);
        }

        return $this;
    }

    public function removeFavoriteMovie(FavoriteMovies $favoriteMovie): self
    {
        if ($this->favoriteMovies->removeElement($favoriteMovie)) {
            $favoriteMovie->removeIdFilm($this);
        }

        return $this;
    }
}
