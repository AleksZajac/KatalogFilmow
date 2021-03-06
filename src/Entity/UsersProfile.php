<?php
/*
 * UsersProfile
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UsersProfile.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UsersProfileRepository")
 */
class UsersProfile
{
    /**
     * Primary Key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $name;

    /**
     * Surname.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $surname;

    /**
     * Login.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $login;

    /**
     * User.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="usersprofile", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * Comments.
     *
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="login")
     */
    private $comments;

    /**
     * UsersProfile constructor.
     */
    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     *
     * @return UsersProfile
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for Surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string $surname Surname
     *
     * @return UsersProfile
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Getter for Login.
     *
     * @return string|null City
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     *
     * @return UsersProfile
     */
    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Getter for User.
     *
     * @return User|ArrayCollection User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for User.
     *
     * @param User $user User
     *
     * @return UsersProfile
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comments $comment
     *
     * @return $this
     */
    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setLogin($this);
        }

        return $this;
    }

    /**
     * @param Comments $comment
     *
     * @return $this
     */
    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getLogin() === $this) {
                $comment->setLogin(null);
            }
        }

        return $this;
    }
}
