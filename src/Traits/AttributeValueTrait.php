<?php

namespace Macareux\ContentTranslator\Traits;

use Concrete\Core\Entity\Attribute\Value\PageValue;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;

trait AttributeValueTrait
{
    public function getPageValueIdentifier(PageValue $value): string
    {
        return $value->getPageID() . '_' . $value->getVersionID() . '_' . $value->getAttributeKey()->getAttributeKeyID();
    }

    public function getPageValueToEdit(string $identifier, Page $page): ?PageValue
    {
        $chunks = explode('_', $identifier);
        $cID = $chunks[0];
        $cvID = $chunks[1];
        $akID = $chunks[2];

        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $em */
        $em = $app->make(EntityManagerInterface::class);
        $repository = $em->getRepository(PageValue::class);

        return $repository->find(['cID' => $cID, 'cvID' => $cvID, 'attribute_key' => $akID]);
    }
}
