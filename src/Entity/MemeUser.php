<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemeUserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class MemeUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meme", mappedBy="created_by", orphanRemoval=true)
     */
    private $memes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upvote", mappedBy="created_by", orphanRemoval=true)
     */
    private $upvotes;


    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->memes = new ArrayCollection();
        $this->upvotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Meme[]
     */
    public function getMemes(): Collection
    {
        return $this->memes;
    }

    public function addMeme(Meme $meme): self
    {
        if (!$this->memes->contains($meme)) {
            $this->memes[] = $meme;
            $meme->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMeme(Meme $meme): self
    {
        if ($this->memes->contains($meme)) {
            $this->memes->removeElement($meme);
            // set the owning side to null (unless already changed)
            if ($meme->getCreatedBy() === $this) {
                $meme->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Upvote[]
     */
    public function getUpvotes(): Collection
    {
        return $this->upvotes;
    }

    public function addUpvote(Upvote $upvote): self
    {
        if (!$this->upvotes->contains($upvote)) {
            $this->upvotes[] = $upvote;
            $upvote->setCreatedBy($this);
        }

        return $this;
    }

    public function removeUpvote(Upvote $upvote): self
    {
        if ($this->upvotes->contains($upvote)) {
            $this->upvotes->removeElement($upvote);
            // set the owning side to null (unless already changed)
            if ($upvote->getCreatedBy() === $this) {
                $upvote->setCreatedBy(null);
            }
        }

        return $this;
    }
}
