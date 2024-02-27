<?php
namespace Training\MessageQueue\Model\Queue;

// use Magento\Framework\App\ResourceConnection;
// use Magento\Framework\MessageQueue\MessageLockException;
// use Magento\Framework\MessageQueue\ConnectionLostException;
// use Magento\Framework\Exception\NotFoundException;
// use Magento\Framework\MessageQueue\CallbackInvoker;
// use Magento\Framework\MessageQueue\ConsumerConfigurationInterface;
// use Magento\Framework\MessageQueue\EnvelopeInterface;
// use Magento\Framework\MessageQueue\QueueInterface;
// use Magento\Framework\MessageQueue\LockInterface;
// use Magento\Framework\MessageQueue\MessageController;
// use Magento\Framework\MessageQueue\ConsumerInterface;
// use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class StockNotifyConsumer
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
    public function processMessage(\Training\MessageQueue\Api\Data\Message\MessageInterface $message)
    {
        if ($message->getData('is_send') == 1){
            file_put_contents(BP . '/var/log/comsumer.log', __FILE__ .':' . __LINE__ . PHP_EOL, FILE_APPEND);
            file_put_contents(BP . '/var/log/comsumer.log', 'Product ' . $message->getData('product_id') . ' in stock' . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents(BP . '/var/log/comsumer.log', __FILE__ .':' . __LINE__ . PHP_EOL, FILE_APPEND);
            file_put_contents(BP . '/var/log/comsumer.log', 'Product ' . $message->getData('product_id') . ' out of stock' . PHP_EOL, FILE_APPEND);
        }
    }
}