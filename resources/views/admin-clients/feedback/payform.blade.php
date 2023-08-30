<?php
$products_receipt = [];
foreach($products as $product) {

    $products_receipt[] = [
        'Name' => str_replace("'", ' ', $product['name']),
        'Price' => (string)($product['price'] * 100),
        'Quantity' => 1,
        'Amount' => (string)($product['price'] * 100),
        'PaymentMethod' => 'full_payment',
        'PaymentObject' => 'commodity',
        'Tax' => 'none'
    ];
}?>

@include('common.pay-form',
    ['pay_form'=>[
        'terminalkey'=>config('config.TINKOFF_TERMINAL_KEY'),
        'amount' => $lead->total,
        'order' => $fbuuid,
        'description'=>'Оплата вещей',
        'name' => $lead->clients->name,
        'email' =>  $lead->clients->email,
        'phone' =>  $lead->clients->phone,
        'receipt' => json_encode([
                        "Email" => $lead->clients->email,
                        'Phone' => $lead->clients->phone,
                        'EmailCompany' => 'ps@thecapsula.ru',
                        'Taxation' => 'usn_income_outcome',
                        'Items' => $products_receipt
                    ], JSON_UNESCAPED_UNICODE)
                 ]
            ])

<script>
    window.alert = function(){};
    $('form').submit();
</script>