<?php

namespace Dwuomo\events\EBus;

use Dwuomo\events\event\Event;

/**
 * Interface Ebus
 * @package Dwuomo\events\EBus
 */
interface Ebus
{
    /**
     * @param Event $event
     * @return self
     */
    public function subscribe(Event $event);

    /**
     * @param string $eventName [Object::class]
     * @return self
     */
    public function unsubscribe($eventName);

    /**
     * @return void
     */
    public function flush();

}