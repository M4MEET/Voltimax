<?php declare(strict_types=1);

namespace VoltimaxTheme\Subscriber;

use Psr\Log\LoggerInterface;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\Api\Context\SalesChannelApiSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class CustomHeaderSubscriber implements EventSubscriberInterface
{
    private SystemConfigService $systemConfigService;
    private EntityRepository $mediaRepository;
    private LoggerInterface $logger;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepository $mediaRepository,
        LoggerInterface $logger
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->mediaRepository = $mediaRepository;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'onHeaderPageletLoaded',
        ];
    }

    public function onHeaderPageletLoaded(HeaderPageletLoadedEvent $event): void
    {
        $context = $event->getContext();
        $source = $context->getSource();
        
        if (!$source instanceof SalesChannelApiSource) {
            return;
        }
        
        $salesChannelId = $source->getSalesChannelId();
        $page = $event->getPagelet();

        // Get theme configuration
        $active = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxCustomHeaderActive', $salesChannelId) ?? false;
        $left = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxCustomHeaderTextLeft', $salesChannelId) ?? '';
        $middle = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxCustomHeaderTextMiddle', $salesChannelId) ?? '';
        $right = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxCustomHeaderTextRight', $salesChannelId) ?? '';
        $rightend = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxCustomHeaderTextRightEnd', $salesChannelId) ?? '';
        
        // Get responsive controls
        $leftHideMobile = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarLeftHideMobile', $salesChannelId) ?? true;
        $leftHideTablet = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarLeftHideTablet', $salesChannelId) ?? false;
        $middleHideMobile = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarMiddleHideMobile', $salesChannelId) ?? true;
        $middleHideTablet = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarMiddleHideTablet', $salesChannelId) ?? false;
        $rightHideMobile = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarRightHideMobile', $salesChannelId) ?? false;
        $rightHideTablet = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarRightHideTablet', $salesChannelId) ?? false;
        $rightendHideMobile = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarRightEndHideMobile', $salesChannelId) ?? true;
        $rightendHideTablet = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarRightEndHideTablet', $salesChannelId) ?? false;
        $trustpilotHideMobile = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarTrustpilotHideMobile', $salesChannelId) ?? true;
        $trustpilotHideTablet = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxTopbarTrustpilotHideTablet', $salesChannelId) ?? false;
        
        // Configuration array
        $pluginConfig = [
            'active' => $active,
            'left' => $left,
            'middle' => $middle,
            'right' => $right,
            'rightend' => $rightend,
            'leftHideMobile' => $leftHideMobile,
            'leftHideTablet' => $leftHideTablet,
            'middleHideMobile' => $middleHideMobile,
            'middleHideTablet' => $middleHideTablet,
            'rightHideMobile' => $rightHideMobile,
            'rightHideTablet' => $rightHideTablet,
            'rightendHideMobile' => $rightendHideMobile,
            'rightendHideTablet' => $rightendHideTablet,
            'trustpilotHideMobile' => $trustpilotHideMobile,
            'trustpilotHideTablet' => $trustpilotHideTablet,
        ];

        // Add configuration to page extensions
        $page->addExtension('VoltimaxCustomHeader', new ArrayEntity($pluginConfig));
    }

    private function findMediaById(string $mediaId, Context $context): ?MediaEntity
    {
        $criteria = new Criteria([$mediaId]);
        $criteria->addAssociation('mediaFolder');
        
        return $this->mediaRepository
            ->search($criteria, $context)
            ->get($mediaId);
    }
}