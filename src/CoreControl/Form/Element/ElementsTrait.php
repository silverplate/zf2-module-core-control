<?php

namespace CoreControl\Form\Element;

trait ElementsTrait
{
    /** @var array */
    protected $_elements;

    /**
     * @param array $_elements
     */
    public function setElements($_elements)
    {
        $this->_elements = $_elements;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }
}
