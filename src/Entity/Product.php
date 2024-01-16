<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductDetail::class, orphanRemoval: true)]
    private Collection $productDetail;

    public function __construct()
    {
        $this->productDetail = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ProductDetail>
     */
    public function getProductDetail(): Collection
    {
        return $this->productDetail;
    }

    public function addProductDetail(ProductDetail $productDetail): static
    {
        if (!$this->productDetail->contains($productDetail)) {
            $this->productDetail[] = $productDetail;
            $productDetail->setProduct($this);
        }

        return $this;
    }

    public function removeProductDetail(ProductDetail $productDetail): static
    {
        if ($this->productDetail->removeElement($productDetail)) {
            if ($productDetail->getProduct() === $this) {
                $productDetail->setProduct(null);
            }
        }

        return $this;
    }
}
