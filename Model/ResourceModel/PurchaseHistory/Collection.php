<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\PurchaseHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\PurchaseHistory
 */
class Collection extends AbstractCollection {

    protected $_idFieldName = 'entity_id';

	protected $_eventPrefix = 'sanjeev_customer_purchase_history_collection';

	protected $_eventObject = 'data_collection';

	protected function _construct() {

	$this->_init(\Sanjeev\CustomerPurchaseHistory\Model\PurchaseHistory::class, \Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\PurchaseHistory::class);

	}

}
