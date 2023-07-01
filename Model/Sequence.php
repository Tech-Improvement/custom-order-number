<?php

namespace Anduel\OrderNumberPrefix\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\SalesSequence\Model\Meta;
use Magento\SalesSequence\Model\Sequence as MagentoSequence;
use Magento\Store\Model\ScopeInterface;

class Sequence extends MagentoSequence
{
    /**
     * @var ScopeConfigInterface
     */
    private mixed $scopeConfig;

    /**
     * Sequence constructor.
     * @param Meta $meta
     * @param ResourceConnection $resource
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Meta                 $meta,
        ResourceConnection   $resource,
        ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($meta, $resource);
        $this->scopeConfig = $scopeConfig;
    }

    public function getNextValue(): ?string
    {
        $lastIncrementId = parent::getNextValue();
        $isEnabled = $this->scopeConfig->getValue('ordernumberprefix/general/enable', ScopeInterface::SCOPE_STORE);
        $prefix = $this->scopeConfig->getValue('ordernumberprefix/general/prefix', ScopeInterface::SCOPE_STORE);

        return ($isEnabled && $prefix) ? $prefix . $lastIncrementId : $lastIncrementId;
    }
}
