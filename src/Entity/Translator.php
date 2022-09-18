<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TranslatorRepository")
 * @ORM\Table(name="MdTranslators")
 */
class Translator
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
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $handle;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $class;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $configuration;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     */
    public function setHandle(string $handle): void
    {
        $this->handle = $handle;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    /**
     * @param string $configuration
     */
    public function setConfiguration(string $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
