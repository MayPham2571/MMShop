<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Response;


class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {

        $paypalConfig = config('services.paypal');

        if (!$paypalConfig || !is_array($paypalConfig['settings'])) {
            throw new \Exception('PayPal settings are missing or invalid in config/services.php');
        }

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.client_id'), // Fetch client ID from config
                config('services.paypal.secret')    // Fetch secret from config
            )
        );

        $this->apiContext->setConfig(config('services.paypal.settings'));
    }


    public function createOrder(Request $request)
    {
        dd(config('services.paypal'));

        try {
            // Log the incoming request for debugging
            \Log::info('PayPal Create Order Request:', $request->all());

            // Validate the amount
            $amountInVND = $request->input('amount');
            if (!$amountInVND || !is_numeric($amountInVND) || $amountInVND <= 0) {
                throw new \Exception('Invalid or missing amount for the order.');
            }

            // Convert VND to USD
            $amountInUSD = $this->convertVNDToUSD($amountInVND);
            \Log::info('Amount in USD:', ['amount' => $amountInUSD]);

            // Create PayPal payment
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount->setCurrency('USD')->setTotal($amountInUSD);

            $transaction = new Transaction();
            $transaction->setAmount($amount)->setDescription('Order Payment');

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction]);

            // Attempt to create the payment in PayPal
            $payment->create($this->apiContext);

            // Log the successful creation
            \Log::info('PayPal Order Created:', ['id' => $payment->getId()]);

            // Return the PayPal payment ID and approval URL
            $approvalUrl = $payment->getApprovalLink();
            return response()->json([
                'id' => $payment->getId(),
                'approval_url' => $approvalUrl
            ]);

        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            // Handle PayPal-specific connection errors
            \Log::error('PayPal API Connection Error:', ['error' => $e->getData()]);
            return response()->json(['error' => 'Failed to connect to PayPal. Please try again.'], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            \Log::error('PayPal Create Order Error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function captureOrder(Request $request, $orderID)
    {
        try {
            $payment = Payment::get($orderID, $this->apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->input('payerID'));

            $result = $payment->execute($execution, $this->apiContext);

            return \response()->json($result);
        } catch (\Exception $e) {
            return \response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function convertVNDToUSD($amountInVND)
    {
        $conversionRate = 0.000043; // Example conversion rate
        if (!is_numeric($amountInVND) || $amountInVND <= 0) {
            throw new \Exception('Invalid amount for conversion.');
        }
        return round($amountInVND * $conversionRate, 2);
    }

}
        
