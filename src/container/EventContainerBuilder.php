<?php

namespace Dwuomo\events\container;

/**
 * Class EventContainerBuilder
 * @package Dwuomo\events\container
 */
class EventContainerBuilder
{
    /** @var string */
    private $path;

    /**
     * Build and return a container.
     *
     * @return EventContainer
     */
    public function build()
    {
        $event = new EventContainer();
        $this->scanAndAddEventDependency($this->path, $event);

        return $event;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setEventPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param $path
     * @param $event
     */
    private function scanAndAddEventDependency($path, $event)
    {
        if (is_dir($path)) {
            $elements = scandir($path, 1);

            foreach ($elements as $element) {
                if (is_file($path . $element)) {
                    require "$path$element";
                } else {
                    if ('.' !== $element && '..' !== $element) {
                        $this->scanAndAddEventDependency($path . $element . '/', $event);
                    }
                }
            }
        }
    }


}