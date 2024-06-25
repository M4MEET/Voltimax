<?php declare(strict_types=1);

namespace VoltimaxTheme\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use VoltimaxTheme\Struct\TaxInfoConfigStruct;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class TaxInfoAlertSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private SystemConfigService $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
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
        $taxInfoText = $this->systemConfigService->get('VoltimaxTheme.config.taxInfoText');

        // Use the custom logger
        $this->logger->info('Tax Info Alert Enabled:', ['taxInfoAlert' => $taxInfoAlert]);
        $this->logger->info('Config Product Tax ID:', ['configProductTaxId' => $configProductTaxId]);
        $this->logger->info('Tax Info Text:', ['taxInfoText' => $taxInfoText]);

        if ($taxInfoAlert && $configProductTaxId) {
            $taxInfoConfigStruct = new TaxInfoConfigStruct($configProductTaxId, $taxInfoText);
            $event->getPage()->addExtension('configProductTaxId', $taxInfoConfigStruct);
            $this->logger->info('TaxInfoConfigStruct added:', ['taxInfoConfigStruct' => $taxInfoConfigStruct]);
        } else {
            $this->logger->warning('Tax Info Alert is disabled or Config Product Tax ID is null.');
        }
    }
}