<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MdGlossaryTerms")
 */
class GlossaryTerm
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="GlossaryTranslation", mappedBy="term", cascade={"persist", "remove"})
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $language
     *
     * @return GlossaryTranslation|null
     */
    public function getTranslationByLanguage(string $language): ?GlossaryTranslation
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('language', $language));

        return $this->translations->matching($criteria)->first();
    }
}
