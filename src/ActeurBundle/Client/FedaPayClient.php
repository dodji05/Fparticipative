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

class FedaPayClient
{
//    private $em;
//    public function __construct($secretfedaKey,EntityManager $em)
//    {
//        $this->em = $em;
//
//        \FedaPay\FedaPay::setApiKey("$secretfedaKey");
//    }
//    public function createCustomer(User $user, $paymentToken){
//        $customer = \FedaPay\Customer::create([
//            'email' => $user->getEmail(),
//            "firstname" => "jhon",
//            "lastname" => "Doe",
//            "phone" => "+229 97 76 78 99"
//        ]);
//        $user->setStripeCustomerId($customer->id);
//        $this->em->persist($user);
//        $this->em->flush($user);
//        return $customer;
//
//    }
//
//    public function updateCustomerCard(User $user, $paymentToken) {
//
//        $customer = \FedaPay\Customer::retrieve($user->getStripeCustomerId());
//        $customer->source = $paymentToken;
//        $customer->save();
//    }
//
//    public function createInvoiceItem($amount, User $user, $description) {
//        return \FedaPay\Transaction::create(array(
//            "amount" => $amount,
//            "currency" => [
//                "code" => "xof"
//            ],
//            "items"=> 1,
//            "callback_url"=>"/",
//            "description" => $description
//        ));
//    }
//    public function createInvoice(User $user, $payImmediately = true)
//    {
//        $invoice = \Stripe\Invoice::create(array(
//            "customer" => $user->getStripeCustomerId()
//        ));
//        if ($payImmediately) {
//            // guarantee it charges *right* now
//            $invoice->pay();
//        }
//        return $invoice;
//    }
//    private function fedapayTransactionData()
//    {
//        $customer_data = [
//            'firstname' => 'Junior',
//            'lastname' => 'Gantin',
//            'email' => 'nioperas06@gmail.com',
//            'phone_number' => [
//                'number'  => '66526416',
//                'country' => 'bj'
//            ]
//        ];
//
//        return [
//            'description' => 'Buy e-book!',
//            'amount' => 500,
//            'currency' => ['iso' => 'XOF'],
//            'callback_url' => url('callback'),
//            'customer' => $customer_data
//        ];
//    }
}