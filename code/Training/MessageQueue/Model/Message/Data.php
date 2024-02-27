<?php declare(strict_types=1);

/**
 * @author tjitse (Vendic)
 * Created on 2019-10-21 11:09
 */

namespace Training\MessageQueue\Model\Message;

class Data implements \Training\MessageQueue\Api\Data\Message\MessageInterface
{
    private $_data = [];

    /**
     * @return int 
     */
    public function getProductId()
    {
        return $this->_get('product_id');
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setProductId($id)
    {
        $this->setData('product_id', $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getSentStatus()
    {
        return $this->_get('is_send');
    }

    /**
     * @param int $is_send
     * @return $this
     */
    public function setSentStatus($is_send)
    {
        $this->setData('is_send', $is_send);
        return $this;
    }

    private function _get($key)
    {
        return $this->_data[$key];
    }

    public function setData($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function getData($key = null)
    {
        if (!empty($key)){
            return $this->_data[$key];
        }
        return $this->_data;
    }
}