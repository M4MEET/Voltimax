<?php declare(strict_types=1);

namespace VoltimaxTheme\Struct;

use Shopware\Core\Framework\Struct\Struct;

class TaxInfoConfigStruct extends Struct
{
    protected ?string $taxEntity;
    protected ?string $taxInfoText;

    public function __construct(?string $taxEntity, ?string $taxInfoText)
    {
        $this->taxEntity = $taxEntity;
        $this->taxInfoText = $taxInfoText;
    }

    public function getTaxEntity(): ?string
    {
        return $this->taxEntity;
    }

    public function getTaxInfoText(): ?string
    {
        return $this->taxInfoText;
    }

    public function setTaxEntity(?string $taxEntity): void
    {
        $this->taxEntity = $taxEntity;
    }

    public function setTaxInfoText(?string $taxInfoText): void
    {
        $this->taxInfoText = $taxInfoText;
    }
}