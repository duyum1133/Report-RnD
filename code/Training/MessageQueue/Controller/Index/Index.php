<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Training\MessageQueue\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Backend\App\Action\Context;

/**
 * Catalog index page controller.
 */
class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    private $resultJsonFactory;
    private $jsonHelper;
    private $publisher;

    const TOPIC_NAME = 'rnd.topic';

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        JsonHelper $jsonHelper,
        \Magento\Framework\MessageQueue\PublisherInterface $publisher, // use for publish message in RabbitMQ
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->publisher = $publisher;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $resultJson = $this->resultJsonFactory->create();
            /**
             * Here we are using random product id and product data as $item for message publish
             * @var int $productId 
             * @var array $item
             */
            $publishData = ['id' => '3', 'item' => 'message data'];

            $this->publisher->publish(self::TOPIC_NAME, $this->jsonHelper->jsonEncode($publishData));
            $result = ['msg' => 'success'];
            return $resultJson->setData($result);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage()];
            return $resultJson->setData($result);
        }
    }
}
