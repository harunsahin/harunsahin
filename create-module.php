<?php

$fields = file_get_contents('fields.json');
$encodedFields = base64_encode($fields);
$command = sprintf(
    'php artisan make:module LimakThermalBoutiqueHotel --fields=%s',
    $encodedFields
);

echo "Executing command:\n";
echo $command . "\n\n";

passthru($command); 