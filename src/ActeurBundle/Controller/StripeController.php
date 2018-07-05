<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ActeurBundle\Event\StripeEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Error\SignatureVerification;
use Stripe\Webhook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StripeController extends Controller
{
    /**
     * @Route("/webhooks/stripe", name="stripe_webhook")
     */
    public function webhookAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \Exception('Bad JSON body from Stripe!');
        }
        $eventId = $data['id'];
        $stripeEvent = $this->get('stripe_client')
            ->findEvent($eventId);
        switch ($stripeEvent->type) {
            case 'charge.succeeded':
                // todo - fully cancel the user's subscription
                break;
            default:
                throw new \Exception('Unexpected webhook type form Stripe! '.$stripeEvent->type);
        }
        return new Response('Event Handled: '.$stripeEvent->type);
    }

}
