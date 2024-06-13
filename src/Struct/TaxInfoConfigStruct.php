<?php declare(strict_types=1);

namespace VoltimaxTheme\Struct;

use Shopware\Core\Framework\Struct\Struct;

class TaxInfoConfigStruct extends Struct
{
    protected ?string $taxEntity;

    public function __construct(?string $taxEntity)
    {
        $this->taxEntity = $taxEntity;
    }

    public function getTaxEntity(): ?string
    {
        return $this->taxEntity;
    }

    public function setTaxEntity(?string $taxEntity): void
    {
        $this->taxEntity = $taxEntity;
    }
}
