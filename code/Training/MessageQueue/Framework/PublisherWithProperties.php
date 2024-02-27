<?php

namespace Training\MessageQueue\Framework;

use Magento\Framework\MessageQueue\EnvelopeFactory;
use Magento\Framework\MessageQueue\ExchangeRepository;
use Magento\Framework\MessageQueue\MessageEncoder;
use Magento\Framework\MessageQueue\MessageValidator;
use Magento\Framework\MessageQueue\Publisher\ConfigInterface as PublisherConfig;
use Magento\Framework\MessageQueue\PublisherInterface;

class PublisherWithProperties implements PublisherInterface
{
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    /**
     * @var EnvelopeFactory
     */
    private $envelopeFactory;

    /**
     * @var MessageEncoder
     */
    private $messageEncoder;

    /**
     * @var MessageValidator
     */
    private $messageValidator;

    /**
     * @var PublisherConfig
     */
    private $publisherConfig;

    /**
     * PublisherWithMessageProperties constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     * @param EnvelopeFactory $envelopeFactory
     * @param MessageEncoder $messageEncoder
     * @param MessageValidator $messageValidator
     * @param PublisherConfig $publisherConfig
     */
    public function __construct(
        ExchangeRepository $exchangeRepository,
        EnvelopeFactory $envelopeFactory,
        MessageEncoder $messageEncoder,
        MessageValidator $messageValidator,
        PublisherConfig $publisherConfig
    ) {
        $this->exchangeRepository = $exchangeRepository;
        $this->envelopeFactory = $envelopeFactory;
        $this->messageEncoder = $messageEncoder;
        $this->messageValidator = $messageValidator;
        $this->publisherConfig = $publisherConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function publish($topicName, $data)
    {
        $this->messageValidator->validate($topicName, $data['body']);
        $properties = $data['properties'] ?? [];
        $data = $this->messageEncoder->encode($topicName, $data['body']);
        $envelope = $this->envelopeFactory->create(
            [
                'body' => $data,
                'properties' => array_merge(
                    [
                        'delivery_mode' => 2,
                        'message_id' => md5(uniqid($topicName))
                    ],
                    $properties
                )
            ]
        );
        $connectionName = $this->publisherConfig->getPublisher($topicName)->getConnection()->getName();
        $exchange = $this->exchangeRepository->getByConnectionName($connectionName);
        $exchange->enqueue($topicName, $envelope);
        return null;
    }
}