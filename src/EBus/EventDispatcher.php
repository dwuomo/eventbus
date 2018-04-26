<?php

namespace Dwuomo\events\EBus;

use Dwuomo\events\container\EventContainer;
use Dwuomo\events\event\Event;
use Dwuomo\events\exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class EventDispatcher
{
    /**
     * @var array
     */
    private $events=[];
    /**
     * @var EventLauncher
     */
    private $eventLauncher;

    public function __construct(
        EventContainer $eventContainer,
        ContainerInterface $container
    ) {
        $this->eventLauncher = new EventLauncher(
            $eventContainer,
            $container
        );
    }

    /**
     * @param Event $event
     * @return self
     */
    public function subscribe(Event $event)
    {
        $this->events[get_class($event)] = $event;
        return $this;
    }

    /**
     * @param string $eventName [Object::class]
     * @return self
     */
    public function unsubscribe($eventName)
    {
        unset($this->events[$eventName]);
        return $this;
    }

    /**
     * @return void
     * @throws NotFoundException
     */
    public function flush()
    {
        $this->eventLauncher->raise($this->events);
    }

}