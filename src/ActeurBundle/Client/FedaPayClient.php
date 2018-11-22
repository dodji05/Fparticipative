<?php
/**
 * Created by PhpStorm.
 * User: AFRIQIYA
 * Date: 31/07/2018
 * Time: 15:27
 */

namespace ActeurBundle\Client;


use ActeurBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dump\Container;
use Symfony\Component\HttpFoundation\Session\Session;

class FedaPayClient
{
   use ControllerTrait;
//    private $em;
    private  $session;
   public $container;
    public function __construct($secretfedaKey,Session $session,ContainerInterface $container)
    {
//        $this->em = $em;
        $this->session = $session;
        $this->session =  $session->get("enAttente");
        $this->container = $container;


        \FedaPay\FedaPay::setApiKey("$secretfedaKey");
    }

    public function transactionFeday($montant){

        try {
            $transaction = \FedaPay\Transaction::create(
                $this->fedapayTransactionData($montant)
            );
            $token = $transaction->generateToken();
            return $this->redirect($token->url);
        } catch(\Exception $e) {
            $this->addFlash(
                'notice',
                'Desole une erreur sest produite veuillez ressayer    '
            );
            return $this->container->redirectToRoute('first_inscription');
        }
    }
    public function fedapayTransactionData($frais)
    {
        $customer_data = [
            'firstname' => $this->session->getNom(),
            'lastname' => $this->session->getPrenom(),
            'email' =>$this->session->getEmail(),
            'phone_number' => [
                'number'  => $this->session->getTelephone(),
                'country' => 'bj'
            ]
        ];
        return [
            'description' => 'frais dinscription',
            'amount' => $frais,
            'currency' => ['iso' => 'XOF'],
            'callback_url' =>  $this->generateUrl('first_inscription_fedapay',array(null),0),//'https://soutenirunprojet.yo.fr/inscription-feday',//$this->generateUrl('first_inscription_fedapay') https://soutenirunprojet.yo.fr/http://localhost:8888/FparticipativeV3/web/app_dev.php/inscription-feday,
            'customer' => $customer_data
        ];
    }

//    public function setContainer()
//    {
//        $this->container->
//    }
}