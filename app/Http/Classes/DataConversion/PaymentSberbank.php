<?php

namespace App\Http\Classes\DataConversion;

use App\Http\Models\Common\Payments;

class PaymentSberbank
{
    public function decodedData(): array
    {

        $rows = Payments::whereSource('sber')->get();

        $output = [];
        foreach ($rows as $row) {
            $payload = json_decode($row->payload, true);


            $invoice_id = $row->pay_for == 'products'
                ? isset($payload['payment']) ? $payload['payment']['invoice_id'] : null
                : $payload['invoice_id'] ?? null;

            $amountIn = $row->pay_for == 'products'
                ? isset($payload['payment']) ? $payload['payment']['amount'] : null
                : $payload['amount'] ?? null;

            $items = $row->pay_for == 'products'
                ? $payload['items'] ?? []
                : null;

            $output[] = [
                "id" => $row->id,
                "lead_id" => $row->lead_id,
                "amount" => $row->amount,
                "paid_at" => $row->paid_at,
                "pay_for" => $row->pay_for,
                'invoice_id' => $invoice_id,
                'amountIn' => $amountIn,
                'items' => $items,
                "payment_id" => $row->payment_id,
                "source" => $row->source,
            ];

        }

        return $output;

    }

    public function head()
    {
        return [
            "id",
            "lead_id" => "144800ed-4f64-44f8-9a30-e1b8b4fb7e18",
            "amount" => 990.0,
            "paid_at" => "2021-10-24 14:10:33",
            "pay_for" => "stylist",
            "payload" => null,
            "payment_id" => 1374765,
            "source" => "sber",
        ];
    }


}
