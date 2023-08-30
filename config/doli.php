<?php

return [

    'DOLI_STAGE_API_URI' => "https://partner.dolyame.ru",
    "DOLI_STAGE_WEBHOOK_URI" => env("DOLI_WEBHOOK_URI","https://stage-0.thecapsula.ru/doli/webhook/"),
    "DOLI_STAGE_FAIL_URI" => env("DOLI_FAIL_URI","https://stage-0.thecapsula.ru/doli/fail/"),
    "DOLI_STAGE_SUCCESS_URI" => env("DOLI_SUCCESS_URI","https://stage-0.thecapsula.ru/doli/success/"),

    'DOLI_PROD_API_URI' => "https://partner.dolyame.ru",
    "DOLI_PROD_WEBHOOK_URI" => env("DOLI_WEBHOOK_URI","https://office.thecapsula.ru/doli/webhook/"),
    "DOLI_PROD_FAIL_URI" => env("DOLI_FAIL_URI","https://office.thecapsula.ru/doli/fail/"),
    "DOLI_PROD_SUCCESS_URI" => env("DOLI_SUCCESS_URI","https://office.thecapsula.ru/doli/success/"),

    "auth" => [
        "stage" => [
            "login" => 'test-demo',
            "pass"  => 'uKIPcrjMnpxMp8rrB8mvWARqqyL0rjVz',
        ],
        "prod" => [
            "login" => 'capsula',
            "pass"  => 'a4BaFjuJZU',
        ],


    ],

    "req" => [
        #default_bits          => 2048,
        #default_md            => sha256,
        #default_keyfile       => privkey.pem,
        "distinguished_name" => "req_distinguished_name",
        "attributes" => "req_attributes",
        "req_extensions" => "ext",
    ],

    "req_distinguished_name" => [
        "countryName" => "Country Name (RU)",
        "countryName_min" => 2,
        "countryName_max" => 2,
        "stateOrProvinceName" => "SAMARA",
        "localityName" => "MOSKVA",
        "0.organizationName" => "Kapsula",
        "organizationalUnitName" => "-",
        "commonName" => "Kapsula",
        "commonName_max" => 64,
        "emailAddress" => "shibanovp@gmail.com",
        "emailAddress_max" => 64
    ],

    "req_attributes" => [
        "challengePassword" => "SAMtySAMATA",
        "challengePassword_min" => 4,
        "challengePassword_max" => 20
    ],

    # Extensions to add to a certificate request
    "ext" => [
        "extendedKeyUsage" => "clientAuth",
        "keyUsage" => "nonRepudiation, digitalSignature, keyEncipherment"
    ]

];
