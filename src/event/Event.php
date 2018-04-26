<?php

namespace Dwuomo\events\event;

/**
 * Class Event
 * @package Dwuomo\events\event
 */
abstract class Event
{

    /**
     * @return array
     */
    abstract public function merchanToArray();

    /**
     * @return array
     */
    abstract public function objectToArray();

}