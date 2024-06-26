<?php declare(strict_types=1);

namespace VoltimaxTheme\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use VoltimaxTheme\Struct\TaxInfoConfigStruct;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaxInfoAlertSubscriber implements EventSubscriberInterface
{
    private SystemConfigService $systemConfigService;
    private TranslatorInterface $translator;
    private LoggerInterface $logger;

    public function __construct(SystemConfigService $systemConfigService, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->systemConfigService = $systemConfigService;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded',
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $taxInfoAlert = $this->systemConfigService->get('VoltimaxTheme.config.taxInfoAlert');
        $configProductTaxId = $this->systemConfigService->get('VoltimaxTheme.config.taxEntity');
        $taxInfoCmsPage = $this->systemConfigService->get('VoltimaxTheme.config.taxInfoCmsPage');

        // Fetch snippets using the translator service
        $taxInfoText = $this->translator->trans('VoltimaxTheme.config.taxInfoText');
        $taxInfoTextDetail = $this->translator->trans('VoltimaxTheme.config.taxInfoTextDetail');

        // Debug logging to ensure configuration values are fetched correctly
        $this->logger->debug('Raw Configuration Values', [
            'taxInfoAlert' => $taxInfoAlert,
            'configProductTaxId' => $configProductTaxId,
            'taxInfoText' => $taxInfoText,
            'taxInfoTextDetail' => $taxInfoTextDetail,
            'taxInfoCmsPage' => $taxInfoCmsPage,
        ]);

        if ($taxInfoAlert && $configProductTaxId) {
            $taxInfoConfigStruct = new TaxInfoConfigStruct($configProductTaxId, $taxInfoText);
            $event->getPage()->addExtension('configProductTaxId', $taxInfoConfigStruct);
            $this->logger->info('TaxInfoConfigStruct added:', ['taxInfoConfigStruct' => $taxInfoConfigStruct]);
        } else {
            $this->logger->warning('Tax Info Alert is disabled or Config Product Tax ID is null.');
        }
    }
}
