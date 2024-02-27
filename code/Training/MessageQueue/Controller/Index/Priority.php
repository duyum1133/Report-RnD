<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Training\MessageQueue\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\App\Action\Context;

/**
 * Catalog index page controller.
 */
class Priority extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    private $resultJsonFactory;
    private $jsonHelper;
    private $publisher;
    private $request;
    private $levelMapping = [
        'high' => 3,
        'medium' => 2,
        'low' => 1,
    ];

    const TOPIC_NAME = 'priority.topic';

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        JsonHelper $jsonHelper,
        \Magento\Framework\MessageQueue\PublisherInterface $publisher, // use for publish message in RabbitMQ
        RequestInterface $request
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->publisher = $publisher;
        $this->request = $request;
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

            $paramValue = $this->request->getParam('level');
            $priority = isset($this->levelMapping[$paramValue]) ? $this->levelMapping[$paramValue] : 0;

            $messageData = [
                'body' => $this->jsonHelper->jsonEncode(['id' => '1', 'item' => 'message with priority ' . $priority]),
                'properties' => [
                    'priority' => $priority
                ]
            ];

            $this->publisher->publish(self::TOPIC_NAME, $messageData);
            $result = 'add message success with priority = ' . $priority;
            echo "$result\n";
            // return $resultJson->setData($result);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage()];
            return $resultJson->setData($result);
        }
    }
}
