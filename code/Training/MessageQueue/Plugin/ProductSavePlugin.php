<?php
namespace Training\MessageQueue\Plugin; 

use Training\MessageQueue\Api\Data\Message\MessageInterfaceFactory as MessageFactory;

class ProductSavePlugin
{
    public $publisher;
    public $_messageManager;
    public $stockRegistry;
    public $messageFactory;

    public function __construct( 
        \Magento\Framework\MessageQueue\PublisherInterface $publisher,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        MessageFactory $messageFactory
    ) {
        $this->publisher = $publisher;
        $this->_messageManager = $messageManager;
        $this->stockRegistry = $stockRegistry;
        $this->messageFactory = $messageFactory;
    }

    public function afterSave(\Magento\Catalog\Model\Product $product, $result)
    {
        // Retrieve the product ID
        $productId = $product->getId();

        // Retrieve the stock item for the product
        $stockItem = $this->stockRegistry->getStockItem($productId);

        // Check if the product is in stock
        $isInStock = $stockItem->getIsInStock();
        
        // Perform actions based on stock status
        $data = $this->messageFactory->create();

        if ($isInStock) {
            $data->setProductId($productId);
            $data->setSentStatus(1);
        } else {
            $data->setProductId($productId);
            $data->setSentStatus(0);
        }

        // Send message to queue
        $this->publisher->publish(
            'stock.notify.topic',
            $data
        );

        if ($data) {
            $this->_messageManager->addSuccess(
                __('Message is added to queue!!')
            );
        } else {
            $this->_messageManager->addErrorMessage(
                __('Something Went Wrong!!')
            );
        }  

        return $result;
    }
}