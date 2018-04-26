<?php

namespace Dwuomo\events\EBus;
use Dwuomo\events\container\EventContainer;
use Dwuomo\events\exceptions\NotFoundException;
use Dwuomo\events\listener\Listener;
use Psr\Container\ContainerInterface;


/**
 * Class EventLauncher
 * @package Dwuomo\events\EBus
 */
class EventLauncher
{
    /** @var EventContainer  */
    private $eventContainer;
    /** @var ContainerInterface  */
    private $container;

    /**
     * EventLauncher constructor.
     * @param EventContainer $eventContainer
     * @param ContainerInterface $container
     */
    public function __construct(
        EventContainer $eventContainer,
        ContainerInterface $container
    ) {
        $this->eventContainer = $eventContainer;
        $this->container = $container;
    }

    /**
     * @param array $events
     * @return void
     * @throws NotFoundException
     */
    public function raise(array $events)
    {
        foreach ($events as $k => $v) {
            $listeners = $this->eventContainer->getListeners($k);
            if ($listeners && count($listeners) > 0) {
                $this->listenerBuilder($listeners, $v);
                unset($events[$k]);
            }
        }
    }

    /**
     * @param $listeners
     * @param $event
     * @throws NotFoundException
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