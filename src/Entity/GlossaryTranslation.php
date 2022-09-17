<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MdGlossaryTranslations")
 */
class GlossaryTranslation
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @var GlossaryTerm
     * @ORM\ManyToOne(targetEntity="GlossaryTerm", inversedBy="translations")
     */
    protected $term;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return GlossaryTerm
     */
    public function getTerm(): GlossaryTerm
    {
        return $this->term;
    }

    /**
     * @param GlossaryTerm $term
     */
    public function setTerm(GlossaryTerm $term): void
    {
        $this->term = $term;
    }
}