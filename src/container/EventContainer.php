<?php

namespace Dwuomo\events\container;

/**
 * Class EventContainer
 * @package Dwuomo\events\container
 */
class EventContainer
{
    private $definitions = [];

    public function register($className, array $definitions)
    {
        $this->definitions[$className] = $definitions;
    }

    public function getListeners($eventName)
    {
        return (isset($this->definitions[$eventName]))
            ? $this->definitions[$eventName]
            : null;
    }

}