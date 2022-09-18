<?php

namespace Concrete\Package\MdContentTranslator;

use Concrete\Core\Application\Service\UserInterface\Menu;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Foundation\Service\ProviderList;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Event;
use Concrete\Core\Routing\Router;
use Concrete\Core\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\TranslateRequestRepository;
use Macareux\ContentTranslator\Extractor\ExtractorServiceProvider;
use Macareux\ContentTranslator\Glossary\GlossaryServiceProvider;
use Macareux\ContentTranslator\Publisher\PublisherServiceProvider;
use Macareux\ContentTranslator\Translator\Manager;
use Macareux\ContentTranslator\Utility\UtilityService;

class Controller extends Package
{
    protected $appVersionRequired = '9.0.0';

    protected $pkgHandle = 'md_content_translator';

    protected $pkgVersion = '0.1.0';

    protected $pkgAutoloaderRegistries = [
        'src' => '\Macareux\ContentTranslator',
    ];

    public function getPackageName()
    {
        return t('Macareux Content Translator');
    }

    public function getPackageDescription()
    {
        return t('A package to add interfaces to translate multilingual content. You can translate content manually, or use cloud API.');
    }

    public function install()
    {
        $package = parent::install();

        $this->installContentFile('install/singlepages.xml');
        $this->installContentFile('install/permissions.xml');

        /** @var Manager $manager */
        $manager = $this->app->make(Manager::class);
        $manager->installTranslator('copy', t('Copy'), '\Macareux\ContentTranslator\Translator\CopyTranslator', true);
        $manager->installTranslator('google_translate', tc('BrandName', 'Google Translate'), '\Macareux\ContentTranslator\Translator\GoogleTranslateTranslator');
        $manager->installTranslator('deepl', tc('BrandName', 'DeepL'), '\Macareux\ContentTranslator\Translator\DeeplTranslator');

        return $package;
    }

    public function upgrade()
    {
        parent::upgrade();

        $this->installContentFile('install/singlepages.xml');
        $this->installContentFile('install/permissions.xml');
    }

    public function uninstall()
    {
        parent::uninstall();

        /** @var Connection $connection */
        $connection = $this->app->make(Connection::class);
        $connection->executeQuery('DROP TABLE IF EXISTS MdTranslateContents');
        $connection->executeQuery('DROP TABLE IF EXISTS MdTranslateRequests');
        $connection->executeQuery('DROP TABLE IF EXISTS MdTranslators');
        $connection->executeQuery('DROP TABLE IF EXISTS MdGlossaryTranslations');
        $connection->executeQuery('DROP TABLE IF EXISTS MdGlossaryTerms');
    }

    public function on_start()
    {
        /** @var UtilityService $utility */
        $utility = $this->app->make(UtilityService::class);
        if ($utility->canAccessToTranslateInterface()) {
            /** @var Router $router */
            $router = $this->app->make(RouterInterface::class);
            $router->buildGroup()->setPrefix('/ccm/md_content_translator/dialog')
                ->setNamespace('Concrete\Package\MdContentTranslator\Controller\Dialog')
                ->routes('dialog.php', $this->getPackageHandle())
            ;

            /** @var \Symfony\Component\EventDispatcher\EventDispatcher $director */
            $director = $this->app->make('director');
            $director->addListener('on_page_view', function ($event) {
                /** @var Event $event */
                $page = $event->getPageObject();
                /** @var EntityManagerInterface $em */
                $em = $this->app->make(EntityManagerInterface::class);
                /** @var TranslateRequestRepository $repository */
                $repository = $em->getRepository(TranslateRequest::class);
                $draft = $repository->findDraftByCollection($page);
                $progress = $repository->findProgressByCollection($page);
                $class = 'dialog-launch launch-tooltip';
                if ($draft || $progress) {
                    $class .= ' bg-info text-light';
                }

                /** @var Menu $menu */
                $menu = $this->app->make('helper/concrete/ui/menu');
                $menu->addPageHeaderMenuItem('content_translator', $this->getPackageHandle(), [
                    'icon' => 'fas fa-language',
                    'label' => t('Translate'),
                    'position' => 'left',
                    'linkAttributes' => [
                        'class' => $class,
                        'dialog-width' => 400,
                        'dialog-height' => 300,
                        'dialog-title' => t('Translate'),
                        'data-bs-toggle' => 'tooltip',
                        'data-bs-placement' => 'bottom',
                        'data-bs-original-title' => t('Translate Content'),
                    ],
                ]);
            });

            /** @var ProviderList $serviceProviderList */
            $serviceProviderList = $this->app->make(ProviderList::class);
            $serviceProviderList->registerProvider(ExtractorServiceProvider::class);
            $serviceProviderList->registerProvider(PublisherServiceProvider::class);
            $serviceProviderList->registerProvider(GlossaryServiceProvider::class);

            $asset = AssetList::getInstance();
            $asset->register('javascript', 'content_translator', 'js/translate.js', [], $this->getPackageEntity());
            $asset->registerGroup('content_translator', [
                ['javascript', 'bootstrap'],
                ['javascript', 'content_translator'],
            ]);

            if ($utility->isThirdPartyTranslatorsEnabled()) {
                $this->registerAutoload();
            }
        }
    }

    private function registerAutoload()
    {
        require $this->getPackagePath() . '/vendor/autoload.php';
    }
}
