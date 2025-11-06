<?php

namespace VoltimaxTheme\Subscriber;

use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Context;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ManufacturerMediaSubscriber implements EventSubscriberInterface
{
    private EntityRepository $mediaRepository;

    public function __construct(EntityRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_LOADED_EVENT => 'onProductsLoaded',
        ];
    }

    public function onProductsLoaded(EntityLoadedEvent $event): void
    {
        /** @var ProductEntity $product */
        foreach ($event->getEntities() as $product) {
            $this->addManufacturerMediaToProduct($product, $event->getContext());
        }
    }

    private function addManufacturerMediaToProduct(ProductEntity $product, Context $context): void
    {
        $manufacturer = $product->getManufacturer();
        
        if (!$manufacturer || !$manufacturer->getMediaId()) {
            return;
        }

        $criteria = new \Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria([$manufacturer->getMediaId()]);
        $media = $this->mediaRepository->search($criteria, $context)->first();

        if ($media) {
            $product->addExtension('manufacturerMedia', $media);
        }
    }
}