<?php
namespace Training\MessageQueue\Model\Queue;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\MessageQueue\MessageLockException;
use Magento\Framework\MessageQueue\ConnectionLostException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\MessageQueue\CallbackInvoker;
use Magento\Framework\MessageQueue\ConsumerConfigurationInterface;
use Magento\Framework\MessageQueue\EnvelopeInterface;
use Magento\Framework\MessageQueue\QueueInterface;
use Magento\Framework\MessageQueue\LockInterface;
use Magento\Framework\MessageQueue\MessageController;
use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Framework\MessageQueue\ConsumerInterface;
use Magento\Framework\MessageQueue\MessageInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Consumer
{
    public $jsonHelper;

    public function __construct(
        JsonHelper $jsonHelper
    ) {
        $this->jsonHelper = $jsonHelper;
    }
    /**
     * {@inheritdoc}
     */
    public function processMessage( $message)
    {
        $data = $this->jsonHelper->jsonDecode($message, true);

        \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(__FILE__ .':' . __LINE__);
        \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($data['id']); // 3 
        \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($data['item']); // 'message data'
    }
}