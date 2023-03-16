<?php

namespace Anduel\OrderNumberPrefix\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\SalesSequence\Model\Sequence as MagentoSequence;
use Magento\Store\Model\ScopeInterface;
use Magento\SalesSequence\Model\Meta;
use Magento\Framework\App\ResourceConnection;

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
     * @param ScopeConfigInterface|null $scopeConfig
     */
    public function __construct(
        Meta $meta,
        ResourceConnection $resource,
        ScopeConfigInterface $scopeConfig = null
    ) {
        parent::__construct($meta, $resource);
        $this->scopeConfig = $scopeConfig ?: ObjectManager::getInstance()->get(ScopeConfigInterface::class);
    }

    public function getNextValue(): ?string
    {
        $lastIncrementId = parent::getNextValue();
        $isEnabled = $this->scopeConfig->getValue('ordernumberprefix/general/enable', ScopeInterface::SCOPE_STORE);
        $prefix = $this->scopeConfig->getValue('ordernumberprefix/general/prefix', ScopeInterface::SCOPE_STORE);

        return ($isEnabled && $prefix) ? $prefix . $lastIncrementId : $lastIncrementId;
    }
}
