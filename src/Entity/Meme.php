<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemeRepository")
 * @Vich\Uploadable
 */
class Meme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="meme_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MemeUser", inversedBy="memes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private $uploaded;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $uploaded_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Upvote", mappedBy="meme", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $upvotes;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->votes = new ArrayCollection();
        $this->upvotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image_url): self
    {
        $this->image = $image_url;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getUploaded(): ?bool
    {
        return $this->uploaded;
    }

    public function setUploaded(bool $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(?\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;

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
            $upvote->setMeme($this);
        }

        return $this;
    }

    public function removeUpvote(Upvote $upvote): self
    {
        if ($this->upvotes->contains($upvote)) {
            $this->upvotes->removeElement($upvote);
            // set the owning side to null (unless already changed)
            if ($upvote->getMeme() === $this) {
                $upvote->setMeme(null);
            }
        }

        return $this;
    }
}
