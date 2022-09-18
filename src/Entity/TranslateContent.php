<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MdTranslateContents")
 */
class TranslateContent
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_TRANSLATED = 'translated';

    public const TYPE_STRING = 'string';

    public const TYPE_TEXT = 'text';

    public const TYPE_HTML = 'html';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string the name of this content to show it on the translate interface
     * @ORM\Column(type="string", nullable=true)
     */
    protected $label;

    /**
     * @var TranslateRequest
     * @ORM\ManyToOne(targetEntity="TranslateRequest", inversedBy="content")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    protected $request;

    /**
     * @var string the identifier to find object to translate
     * @ORM\Column(type="string")
     */
    protected $source_identifier = '';

    /**
     * @var string|null the field name if the given object has multiple fields
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source_subfield;

    /**
     * @var string the type of object
     * @ORM\Column(type="string")
     */
    protected $source_type = '';

    /**
     * @var string the original content to translate
     * @ORM\Column(type="text")
     */
    protected $content = '';

    /**
     * @var string|null the translated content
     * @ORM\Column(type="text", nullable=true)
     */
    protected $translated;

    /**
     * @var string the status of this translatable content
     * @ORM\Column(type="string", length=10)
     */
    protected $status = '';

    /**
     * @var string the type of translatable content
     * @ORM\Column(type="string", length=6)
     */
    protected $type = '';

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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return TranslateRequest
     */
    public function getRequest(): TranslateRequest
    {
        return $this->request;
    }

    /**
     * @param TranslateRequest $request
     */
    public function setRequest(TranslateRequest $request): void
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getSourceIdentifier(): string
    {
        return $this->source_identifier;
    }

    /**
     * @param string $source_identifier
     */
    public function setSourceIdentifier(string $source_identifier): void
    {
        $this->source_identifier = $source_identifier;
    }

    /**
     * @return string|null
     */
    public function getSourceSubfield(): ?string
    {
        return $this->source_subfield;
    }

    /**
     * @param string|null $source_subfield
     */
    public function setSourceSubfield(?string $source_subfield): void
    {
        $this->source_subfield = $source_subfield;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->source_type;
    }

    /**
     * @param string $source_type
     */
    public function setSourceType(string $source_type): void
    {
        $this->source_type = $source_type;
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
     * @return string|null
     */
    public function getTranslated(): ?string
    {
        return $this->translated;
    }

    /**
     * @param string|null $translated
     */
    public function setTranslated(string $translated): void
    {
        $this->translated = $translated;
    }

    public function clearTranslated(): void
    {
        $this->translated = null;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
