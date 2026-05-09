<?php 
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Model\ResourceModel;

class PurchaseHistory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function _construct()
	{
		$this->_init("sanjeev_customer_purchase_history","entity_id");
	}
}
