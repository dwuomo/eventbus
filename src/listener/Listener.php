<?php

namespace Dwuomo\events\listener;

use Dwuomo\events\event\Event;

/**
 * Interface Listener
 * @package Dwuomo\events\listener
 */
interface Listener
{
    /**
     * @param Event $event
     * @param array $options
     * @return void
     */
    public function handle(Event $event, array $options);

}