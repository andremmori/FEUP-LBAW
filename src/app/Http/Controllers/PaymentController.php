<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaymentController extends Controller
{
    private $apiContext;

    private $event;
    private $user;

    public function __construct()
    {
        $payPalConfig = Config::get('services.paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $payPalConfig['client_id'],     // ClientID
                $payPalConfig['secret']      // ClientSecret
            )
        );

        $this->apiContext->setConfig($payPalConfig['settings']);
    }

    public function payWithPayPal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($request->input('price'));
        $amount->setCurrency('EUR');

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription("");

        $callbackUrl = route('paypal.status', [$request->input('event'), $request->input('user')]);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($callbackUrl)
            ->setCancelUrl($callbackUrl);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            //echo $payment;
            //echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
            return redirect()->away($payment->getApprovalLink());
        } catch (PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }

    }

    public function payPalStatus(Request $request, $event, $user)
    {

        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');

        if (!$paymentId || !$payerId || !$token) {
            session()->flash('error', "Payment Cancelled");
            return redirect()->route('event.show', $event);
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() === 'approved') {

            $ticket = DB::transaction(function () use ($user, $event) {
                $ticket = new Ticket();
                $this->authorize('create', $ticket);

                $ticket->iduser = $user;
                $ticket->idevent = $event;

                $ticket->save();
                return $ticket;
            });
            session()->flash('success', $paymentId);
            return redirect()->route('event.show', $event);
        }

        session()->flash('error', "PayPal was unable to perform transaction");
        return redirect()->route('event.show', $event);
    }
}
