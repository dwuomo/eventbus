<?php

namespace Dwuomo\events\EBus;

use Dwuomo\events\container\EventContainer;
use Dwuomo\events\event\Event;
use Dwuomo\events\exceptions\NotFoundException;
use Dwuomo\events\listener\Listener;
use Psr\Container\ContainerInterface;

class EventDispatcher
{
    /**
     * @var EventContainer
     */
    private $eventContainer;
    /**
     * @var array
     */
    private $events=[];
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        EventContainer $eventContainer,
        ContainerInterface $container
    ) {
        $this->eventContainer = $eventContainer;
        $this->container = $container;
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
     */
    public function flush()
    {
        foreach ($this->events as $k => $v) {
            $listeners = $this->eventContainer->getListeners($k);
            if ($listeners && count($listeners) > 0) {
                $this->listenerBuilder($listeners, $v);
                unset($this->events[$k]);
            }
        }
    }

    /**
     * @param $listeners
     * @param $event
     */
    private function listenerBuilder($listeners, $event)
    {
        foreach ($listeners as $listener) {
            try {
                $className = isset($listener[0]) ? $listener[0] : null;
                $options = isset($listener[1]) ? $listener[1] : null;

                if (!$className)
                    throw new NotFoundException('Clean empty Events registered, please!');

                if (!$options)
                    throw new NotFoundException('Options array param its necesary');

                /** @var Listener $classListener */
                $classListener = $this->container->get($className);
                $classListener->handle($event, $options);
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
    }

}