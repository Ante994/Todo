<?php

namespace AppBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetDisplayName implements EventSubscriberInterface
{
    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $user->setDisplayName($user->getFirstName().$user->getLastname()[0]);
    }

    public static function getSubscribedEvents()
    {
        // The solution is to add a lower priority to call your listener after EmailConfirmationEvent
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => [
                ['onRegistrationSuccess', -10],
            ],
        ];
    }
}

