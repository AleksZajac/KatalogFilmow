<?php
/*
 *  Comment Entity
 */

namespace App\Entity;

use App\Repository\CommentsRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentsRepository", repositoryClass=CommentsRepository::class)
 * @ORM\Table (name="comments")
 */
class Comments
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
     * Primary Key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Content.
     *
     * @ORM\Column(type="text")
     *
     * @Assert\Type (type="text")
     * @Assert\NotBlank
     * @Assert\Length (
     *     allowEmptyString="false",
     *     min="3",
     *     max="1000",
     * )
     */
    private $content;

    /**
     * Created At.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type (type="\DateRimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * Films.
     *
     * @ORM\ManyToOne(targetEntity=Films::class, inversedBy="comment", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $films;

    /**
     * UserProfile.
     *
     * @ORM\ManyToOne(targetEntity=UsersProfile::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $login;

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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface $date
     *
     * @return $this
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Films|null
     */
    public function getFilms(): ?Films
    {
        return $this->films;
    }

    /**
     * @param Films|null $films
     *
     * @return $this
     */
    public function setFilms(?Films $films): self
    {
        $this->films = $films;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }


    /**
     * @param UsersProfile|null $login
     *
     * @return $this
     */
    public function setLogin(?UsersProfile $login)
    {
        $this->login = $login;

        return $this;
    }
}
