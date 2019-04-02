<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UpvoteRepository")
 */
class Upvote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meme", inversedBy="upvotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MemeUser", inversedBy="upvotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeme(): ?Meme
    {
        return $this->meme;
    }

    public function setMeme(?Meme $meme): self
    {
        $this->meme = $meme;

        return $this;
    }

    public function getCreatedBy(): ?MemeUser
    {
        return $this->created_by;
    }

    public function setCreatedBy(?MemeUser $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }
}
