<?php

namespace Training\SimulatorEmail\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\MessageQueue\PublisherInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class SendEmailsCommandPublisher extends Command
{
    const EMAIL_TEMPLATE = 'rabbitmq_simulator_email_template'; // Update with your email template ID
    const TOPIC_NAME = 'email.topic';

    private $state;
    private $logger;
    private $publisher;
    private $jsonHelper;

    public function __construct(
        State $state,
        LoggerInterface $logger,
        PublisherInterface $publisher,
        JsonHelper $jsonHelper
    ) {
        parent::__construct();
        $this->state = $state;
        $this->logger = $logger;
        $this->publisher = $publisher;
        $this->jsonHelper = $jsonHelper;
    }

    protected function configure()
    {
        $this->setName('rnd:send_emails:asynchronous')
            ->setDescription('Send emails to 20 persons and log the execution time');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode('frontend'); // Set the area code to frontend

            $startTime = microtime(true);

            // Send emails to 20 recipients
            for ($i = 1; $i <= 20; $i++) {
                $messageData = ['id' => $i, 'email' => 'recipient@example.com', 'message' => 'message data'];
                $this->publisher->publish(self::TOPIC_NAME, $this->jsonHelper->jsonEncode($messageData));
            }

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            // Log execution time
            $this->logger->info('Publish email successfully. Execution time: ' . $executionTime . ' seconds');
            echo 'Publish email successfully. Execution time: ' . $executionTime . ' seconds' . PHP_EOL;

            return $this::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error('Error sending emails: ' . $e->getMessage());
            return $this::FAILURE;
        }
    }
}
