<?php 

namespace Training\MessageQueue\Api\Data\Message;

interface MessageInterface 
{
    /**
     * @param int
     * @return $this
     */
    public function setProductId($data);

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int
     * @return $this
     */
    public function setSentStatus($item);

    /**
     * @return int
     */
    public function getSentStatus();
}