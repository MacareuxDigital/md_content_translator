<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\Translator;
use Macareux\ContentTranslator\Entity\TranslatorRepository;

class Manager implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTranslatorByID(int $id): ?Translator
    {
        /** @var TranslatorRepository $translatorRepository */
        $translatorRepository = $this->entityManager->getRepository(Translator::class);

        return $translatorRepository->find($id);
    }

    public function getTranslatorByHandle(string $handle): ?Translator
    {
        /** @var TranslatorRepository $translatorRepository */
        $translatorRepository = $this->entityManager->getRepository(Translator::class);

        return $translatorRepository->findOneByHandle($handle);
    }

    public function getTranslatorService(Translator $entity): ?TranslatorInterface
    {
        /** @var TranslatorInterface|null $service */
        $service = $this->app->make($entity->getClass());
        if ($service) {
            $configuration = $entity->getConfiguration();
            if ($configuration) {
                $service->loadConfiguration($entity->getConfiguration());
            }
            return $service;
        }

        return null;
    }

    /**
     * @return Translator[]
     */
    public function getInstalledTranslators(): array
    {
        $translatorRepository = $this->entityManager->getRepository(Translator::class);

        return $translatorRepository->findAll();
    }

    /**
     * @return Translator[]
     */
    public function getAvailableTranslators(): array
    {
        $translatorRepository = $this->entityManager->getRepository(Translator::class);

        return $translatorRepository->findBy(['active' => true]);
    }

    public function installTranslator(string $handle, string $name, string $class, bool $active = false): void
    {
        $translator = new Translator();
        $translator->setHandle($handle);
        $translator->setName($name);
        $translator->setClass($class);
        $translator->setActive($active);

        $this->entityManager->persist($translator);
        $this->entityManager->flush();
    }
}