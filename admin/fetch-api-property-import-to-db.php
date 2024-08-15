<?php
// Insert Property In Database
function insert_property_in_db(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.realestate.com.au/listing/v1/export',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer 1469db7a-c466-4642-a88f-c9c4ac8d3015'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}


function insert_property_import_in_db() {
    $api_response = insert_property_in_db();

    // Ensure that the API response is a valid XML string
    $xml = @simplexml_load_string($api_response);
    if ($xml === false) {
        return "Failed to parse XML.";
    }

    // Convert the XML to JSON and then decode it to an associative array
    $api_response = json_encode($xml, JSON_PRETTY_PRINT);
    $properties = json_decode($api_response, true);

    // Check if 'rental' key exists
    if (!array_key_exists('rental', $properties)) {
        return "The 'rental' key does not exist in the API response.";
    }

    // Extract the rental data
    $rental_data = $properties['rental'];

    // Check if rental_data is a list or a single rental
    if (isset($rental_data[0])) {
        // rental_data is a list of rentals
        $rentals = $rental_data;
    } else {
        // rental_data is a single rental, convert it to an array for consistency
        $rentals = [$rental_data];
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_property';
    $status = "pending";

    // Iterate through each rental and insert the uniqueID into the database
    foreach ($rentals as $rental) {
        // Check if 'uniqueID' exists in the rental property
        if (!array_key_exists('uniqueID', $rental)) {
            continue; // Skip this rental if uniqueID is not present
        }

        $uniqueid = $rental['uniqueID'];

        // Prepare the SQL query
        $sql = $wpdb->prepare("INSERT INTO $table_name (uniqueid, status) VALUES (%s, %s)", $uniqueid, $status);
        $result = $wpdb->query($sql);

        if ($result === false) {
            return "Failed to insert property data for Unique ID: $uniqueid.";
        }
    }

    return "All properties inserted successfully.";
}
