<?php
namespace Training\SimulatorEmail\Model\Queue;

use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Mail\Template\TransportBuilder;

class Consumer
{
    const EMAIL_TEMPLATE = 'rabbitmq_simulator_email_template'; // Update with your email template ID
    public $totalTime = 0;
    private $jsonHelper;
    private $transportBuilder;

    public function __construct(
        JsonHelper $jsonHelper,
        TransportBuilder $transportBuilder
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->transportBuilder = $transportBuilder;
    }
    /**
     * {@inheritdoc}
     */
    public function processMessage( $message)
    {
        $data = $this->jsonHelper->jsonDecode($message, true);
        $startTime = microtime(true);
        $this->sendEmail($data['email'].'.'.$data['id'], $data['message'], $data['id']);
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->totalTime+=$executionTime;

        file_put_contents(BP . '/var/log/custom.log', __FILE__ .':' . __LINE__ . PHP_EOL, FILE_APPEND);
        file_put_contents(BP . '/var/log/custom.log', $executionTime . PHP_EOL, FILE_APPEND);

        // file_put_contents(BP . '/var/log/custom.log', __FILE__ .':' . __LINE__ . PHP_EOL, FILE_APPEND);
        // file_put_contents(BP . '/var/log/custom.log',  $this->totalTime . PHP_EOL, FILE_APPEND);
    }

    private function sendEmail($recipientEmail, $recipientName, $emailNumber)
    {
        $transport = $this->transportBuilder->setTemplateIdentifier(self::EMAIL_TEMPLATE)
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars(['data' => ['name' => $recipientName, 'email_number' => $emailNumber]])
            ->setFrom(['email' => 'sender@example.com', 'name' => 'Sender'])
            ->addTo($recipientEmail, $recipientName)
            ->getTransport();

        $transport->sendMessage();
    }
}