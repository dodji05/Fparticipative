<?php
/**
 * Created by PhpStorm.
 * User: AFRIQIYA
 * Date: 04/05/2018
 * Time: 15:22
 */
namespace ActeurBundle\Event;

use Stripe\ApiResource;
use Stripe\Event;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class StripeEvent extends BaseEvent
{
    protected $event;

    /**
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->event->type;
    }

    /**
     * @return ApiResource
     */
    public function getResource()
    {
        return $this->event->data->object;
    }
}