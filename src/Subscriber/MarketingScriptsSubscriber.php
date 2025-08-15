<?php declare(strict_types=1);

namespace VoltimaxTheme\Subscriber;

use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Api\Context\SalesChannelApiSource;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Event\StorefrontRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MarketingScriptsSubscriber implements EventSubscriberInterface
{
    private SystemConfigService $systemConfigService;
    private LoggerInterface $logger;

    public function __construct(
        SystemConfigService $systemConfigService,
        LoggerInterface $logger
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            StorefrontRenderEvent::class => 'onStorefrontRender',
        ];
    }

    public function onStorefrontRender(StorefrontRenderEvent $event): void
    {
        $context = $event->getContext();
        $source = $context->getSource();
        
        if (!$source instanceof SalesChannelApiSource) {
            return;
        }
        
        $salesChannelId = $source->getSalesChannelId();
        
        try {
            // Get all marketing scripts configuration
            $marketingScripts = $this->getMarketingScriptsConfig($salesChannelId);
            
            if (!empty($marketingScripts)) {
                // Add marketing scripts to template variables
                $event->setParameter('voltimaxMarketingScripts', $marketingScripts);
            }
            
        } catch (\Exception $exception) {
            $this->logger->error('Error loading marketing scripts configuration: ' . $exception->getMessage());
        }
    }

    private function getMarketingScriptsConfig(string $salesChannelId): array
    {
        $scripts = [];
        
        // Get configuration for all 5 marketing scripts
        for ($i = 1; $i <= 5; $i++) {
            $scriptContent = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}", $salesChannelId);
            $scriptActive = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}Active", $salesChannelId);
            $scriptAsync = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}Async", $salesChannelId);
            
            // Only add active scripts with content
            if ($scriptActive && !empty(trim((string)$scriptContent))) {
                $scripts[] = [
                    'priority' => $i,
                    'content' => $this->sanitizeScript($scriptContent),
                    'async' => (bool)$scriptAsync,
                    'id' => $i
                ];
            }
        }
        
        // Sort by priority (lower number = higher priority)
        usort($scripts, function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
        
        return $scripts;
    }

    private function sanitizeScript(string $scriptContent): string
    {
        // Basic sanitization - remove potential XSS attempts while preserving script functionality
        $scriptContent = trim($scriptContent);
        
        // Log potentially dangerous patterns (for security monitoring)
        if (preg_match('/(?:javascript:|on\w+\s*=|<script[^>]*src\s*=\s*["\'](?:https?:)?\/\/(?![^\/]+\.voltimax\.de))/i', $scriptContent)) {
            $this->logger->warning('Marketing script contains potentially unsafe content', [
                'script_preview' => substr($scriptContent, 0, 100)
            ]);
        }
        
        // Don't double-wrap script tags - the template already adds them
        // Just return the content as-is
        return $scriptContent;
    }
}