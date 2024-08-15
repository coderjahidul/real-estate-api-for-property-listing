<?php
// function kapi_fetch_single_property_from_api() {
//     $curl = curl_init();

//     curl_setopt_array($curl, array(
//         CURLOPT_URL => 'https://api.realestate.com.au/listing/v1/export',
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => '',
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 0,
//         CURLOPT_FOLLOWLOCATION => true,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => 'GET',
//         CURLOPT_HTTPHEADER => array(
//             'Authorization: Bearer 403fe4d8-9d33-4133-96f3-75a05b4217b5'
//         ),
//     ));

//     $response = curl_exec($curl);

//     if (curl_errno($curl)) {
//         // Handle curl error
//         echo 'Curl error: ' . curl_error($curl);
//         curl_close($curl);
//         return false;
//     }

//     curl_close($curl);
//     return $response;
// }

// function reapi_get_property_import() {
//     $api_response = kapi_fetch_single_property_from_api();

//     if (!$api_response) {
//         echo 'Failed to fetch data from API.';
//         return;
//     }

//     // Check if the response is valid XML
//     $xml = simplexml_load_string($api_response);
//     if ($xml === false) {
//         echo 'Failed to parse XML.';
//         return;
//     }

//     // Convert XML to JSON
//     $propertyJson = json_encode($xml, JSON_PRETTY_PRINT);

//     // Decode JSON to PHP array
//     $propertyArray = json_decode($propertyJson, true);

//     if (json_last_error() !== JSON_ERROR_NONE) {
//         echo 'JSON decode error: ' . json_last_error_msg();
//         return;
//     }

//     // Print or process the property data
//     print_r($propertyArray);

// }
