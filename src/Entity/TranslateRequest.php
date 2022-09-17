<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TranslateRequestRepository")
 * @ORM\Table(name="MdTranslateRequests")
 * @ORM\HasLifecycleCallbacks
 */
class TranslateRequest
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_PROGRESS = 'progress';

    public const STATUS_PUBLISHED = 'published';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $source_language = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $target_language = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=9)
     */
    protected $status = '';

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cID;

    /**
     * @var TranslateContent[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="TranslateContent", mappedBy="request", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    protected $contents;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSourceLanguage(): string
    {
        return $this->source_language;
    }

    /**
     * @param string $source_language
     */
    public function setSourceLanguage(string $source_language): void
    {
        $this->source_language = $source_language;
    }

    /**
     * @return string
     */
    public function getTargetLanguage(): string
    {
        return $this->target_language;
    }

    /**
     * @param string $target_language
     */
    public function setTargetLanguage(string $target_language): void
    {
        $this->target_language = $target_language;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDisplayStatus(): string
    {
        switch ($this->getStatus()) {
            case self::STATUS_DRAFT:
                $status = t('Draft');
                break;
            case self::STATUS_CANCELED:
                $status = t('Canceled');
                break;
            case self::STATUS_PROGRESS:
                $status = t('In Progress');
                break;
            case self::STATUS_PUBLISHED:
                $status = t('Published');
                break;
            default:
                $status = $this->getStatus();
        }

        return $status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getCID(): ?int
    {
        return $this->cID;
    }

    /**
     * @param int $cID
     */
    public function setCID(int $cID): void
    {
        $this->cID = $cID;
    }

    /**
     * @return ArrayCollection|TranslateContent[]
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->created_at = new \DateTime();
    }
}
