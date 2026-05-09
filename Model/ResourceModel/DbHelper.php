<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Model\ResourceModel;
/**
 * Helper class for direct DB operations related to customer purchase history.
 */
class DbHelper extends \Magento\Framework\DB\Helper
{
    /**
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param string $modulePrefix
     */
    public function __construct(
    \Magento\Framework\App\ResourceConnection $resource,
    $modulePrefix = 'Sanjeev_CustomerPurchaseHistory')
    {
        parent::__construct($resource, $modulePrefix);
    }

    public function quickSavePurchaseData($data)
    {
        $connection = $this->getConnection();
        $mainTable = $connection->getTableName("sanjeev_customer_purchase_history");
        $select = $connection->select()->from(['main_table' => $mainTable],['entity_id' => 'entity_id']);
        $select->where('customer_id = ?', $data['customer_id']);
        $select->where('store_id = ?', $data['store_id']);
        $entityId = (int)$connection->fetchOne($select);

        if ($entityId === 0) {
            $connection->insert($mainTable, $data);
        } else {
            $connection->update($mainTable, $data, ['entity_id = ?' => $entityId]);
        }

    }
}
