<?php


require  '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../config/.env');
$dotenv->load();

$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

function calculateOrderAmount(int $amount): int {
    
      if ($amount < 1) {
          $amount = 1;
    }
    return $amount*100;
    // Replace this constant with a calculation of the order's amount
    // Calculate the order total on the server to prevent
    // people from directly manipulating the amount on the client
    // return 1400;
}

header('Content-Type: application/json');

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);
    $amount = $jsonObj->amount;

    // TODO : Create a PaymentIntent with amount and currency in '$paymentIntent'
    
    $stripe->paymentIntents->create([
                             'amount'=>calculateOrderAmount($amount),
                             'currency'=>'eur',
        ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

