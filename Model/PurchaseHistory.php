<?php 
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Model;

class PurchaseHistory extends \Magento\Framework\Model\AbstractModel
{
	public function _construct()
	{
		$this->_init(\Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\PurchaseHistory::class);
	}
}
