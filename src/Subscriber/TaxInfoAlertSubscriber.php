<?php declare(strict_types=1);

namespace VoltimaxTheme\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use VoltimaxTheme\Struct\TaxInfoConfigStruct;
use Psr\Log\LoggerInterface;

class TaxInfoAlertSubscriber implements EventSubscriberInterface
{
    private SystemConfigService $systemConfigService;
    private LoggerInterface $logger;

    public function __construct(SystemConfigService $systemConfigService, LoggerInterface $logger)
    {
        $this->systemConfigService = $systemConfigService;
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
        $configProductTaxId = $this->systemConfigService->get('VoltimaxTheme.config.taxEntity');
        $this->logger->info('Config Product Tax ID:', ['configProductTaxId' => $configProductTaxId]);

        if ($configProductTaxId) {
            $taxInfoConfigStruct = new TaxInfoConfigStruct($configProductTaxId);
            $event->getPage()->addExtension('configProductTaxId', $taxInfoConfigStruct);
            $this->logger->info('TaxInfoConfigStruct added:', ['taxInfoConfigStruct' => $taxInfoConfigStruct]);
        } else {
            $this->logger->warning('Config Product Tax ID is null or not set.');
        }
    }
}
