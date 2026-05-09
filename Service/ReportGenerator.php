<?php 
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Service;

/**
 * Class ReportGenerator
 * @package Sanjeev\CustomerPurchaseHistory\Service
 */
class ReportGenerator extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $_customerCollectionFactory;

    /**
     * @var \Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\DbHelper
     */
    protected $_dbHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Sanjeev\CustomerPurchaseHistory\Model\Config
     */
    protected $_config;


    public function __construct(
    \Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
    \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
    \Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\DbHelper $dbHelper,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Sanjeev\CustomerPurchaseHistory\Model\Config $config
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->_dbHelper = $dbHelper;
        $this->_storeManager = $storeManager;
        $this->_config = $config;

    }

    public function generate()
    {
        $enabledStoreIds = $this->getEnabledStores();

        if (empty($enabledStoreIds)) {
            return false;
        }

        $customerCollection = $this->getCustomerCollection($enabledStoreIds);
        $totalCustomers = $customerCollection->getSize();

        if ($totalCustomers === 0) {
            return false;
        }

        $storeStatuses = [];
        foreach ($enabledStoreIds as $storeId) {
            $storeStatuses[$storeId] = $this->getOrderStatuses($storeId);
        }

        $pageSize = 100;
        $totalPages = ceil($totalCustomers / $pageSize);

        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++) {
            $customerCollection->setPageSize($pageSize);
            $customerCollection->setCurPage($currentPage);
            foreach ($customerCollection as $customer) {
                $orderCollection = $this->_orderCollectionFactory->create();
                $orderCollection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
                $orderCollection->addFieldToSelect('base_currency_code');
                $orderCollection->selectLastPurchaseDate();
                $orderCollection->setCustomerId($customer->getId());
                $orderCollection->addSumAvgTotals($customer->getStoreId());
                $orderCollection->applyDateRangeFilter($customer->getCreatedAt());
                $orderCollection->addFieldToFilter('status', ['in' => $storeStatuses[$customer->getStoreId()]]);

                $aggregates = $orderCollection->getFirstItem();
                if ($aggregates->getData('orders_sum_amount') === null) {
                    continue; // Skip customers with no orders in the specified date range
                }

                $data = $aggregates->getData();
                $this->_dbHelper->quickSavePurchaseData([
                    'customer_id' => $customer->getId(),
                    'store_id' => $customer->getStoreId(),
                    'total_purchase_amount' => $data['orders_sum_amount'],
                    'last_purchase_date' => $data['last_purchase_date'],
                    'avg_purchase_amount' => $data['orders_avg_amount'],
                    'currency_code' => $data['base_currency_code']
                ]);
            }
        }
    }

    protected function getCustomerCollection(array $storeIds)
    {
        $customerCollection = $this->_customerCollectionFactory->create();
        $customerCollection->addAttributeToSelect('entity_id');
        $customerCollection->addAttributeToSelect('store_id');
        $customerCollection->addAttributeToSelect('created_at');
        $customerCollection->addFieldToFilter('store_id', ['in' => $storeIds]);
        return $customerCollection;
    }

    protected function getEnabledStores()
    {
        $stores = $this->_storeManager->getStores();
        $enabledStoreIds = [];
        foreach ($stores as $store) {
            if ($this->_config->isEnabled($store->getId())) {
                $enabledStoreIds[] = $store->getId();
            }
        }
        return $enabledStoreIds;
    }

    protected function getOrderStatuses($storeId = null)
    {
        $statuses = ['complete', 'processing']; // default statuses

        $configStatuses = $this->_config->getOrderStatuses($storeId);
        if (is_string($configStatuses)) {
             $unfilteredStatuses = explode(',', $configStatuses);
             $filteredStatuses = array_filter($unfilteredStatuses);

             if (!empty($filteredStatuses)) {
                $statuses = $filteredStatuses;
            }
        }

        return $statuses;
    }
}
