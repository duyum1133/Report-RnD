<?php
namespace Training\MessageQueue\Model\Queue;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Psr\Log\LoggerInterface;

class PriorityConsumer extends ConsumerConfiguration
{
    public $jsonHelper;
    private $logger;

    public function __construct(
        JsonHelper $jsonHelper,
        LoggerInterface $logger
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
    }
    /**
     * {@inheritdoc}
     */

    public function process($message) {
        echo "Received message " . $message . PHP_EOL;
    }

    private function processHighPriorityMessage($messageData) {
        $this->logger->info('Processing high-priority message: ' . json_encode($messageData));
    }

    private function processMediumPriorityMessage($messageData) {
        $this->logger->info('Processing medium-priority message: ' . json_encode($messageData));
    }

    private function processLowPriorityMessage($messageData) {
        $this->logger->info('Processing low-priority message: ' . json_encode($messageData));
    }

    protected function getPriorityFromHeaders(array $headers)
    {
        return $headers['priority'];
    }
}