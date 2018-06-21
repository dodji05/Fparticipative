<?php
/**
 * Created by PhpStorm.
 * User: AFRIQIYA
 * Date: 04/05/2018
 * Time: 14:30
 */
namespace ActeurBundle\Client;

use AdminBundle\Entity\Donateurs;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;
class StripeClient
{
    private $config;
    private $em;
    private $logger;

    public function __construct($secretKey, array $config, EntityManagerInterface $em, LoggerInterface $logger)
    {
        \Stripe\Stripe::setApiKey($secretKey);
        $this->config = $config;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function createPremiumCharge(Donateurs $user, $token)
    {
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $this->config['decimal'] ? $this->config['premium_amount'] * 100 : $this->config['premium_amount'],
                'currency' => $this->config['currency'],
                'description' => 'Premium blog access',
                'source' => $token,
                'receipt_email' => $user->getEmail(),
            ]);
        } catch (\Stripe\Error\Base $e) {
            $this->logger->error(sprintf('%s exception encountered when creating a premium payment: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]);

            throw $e;
        }

        $user->setChargeId($charge->id);
       // $user->setPremium($charge->paid);
        $this->em->flush();
    }
}