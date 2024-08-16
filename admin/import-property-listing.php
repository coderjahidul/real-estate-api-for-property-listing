<?php

function reapi_get_single_property_import() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_property';

    $properties = $wpdb->get_results(
        "SELECT id, value FROM $table_name WHERE status = 'panding' ORDER BY id ASC LIMIT 1"
    );
    $serial_id = $properties[0]->id;
    $properties = json_decode($properties[0]->value, true);
    
    // Extract Property Attributes
    $property_attributes = $properties['@attributes'] ?? [];
    $property_status = $properties['status'] ?? '';
    $property_uniqueID = $properties['uniqueID'] ?? '';
    $property_agentID = $properties['agentID'] ?? '';
    $property_listingID = $properties['listingId'] ?? '';
    $property_depositTaken = $properties['depositTaken'] ?? '';

    // Extract Listing Agent Details
    $property_listingAgent = $properties['listingAgent'] ?? [];
    $property_agentID = $properties['agentID'] ?? '';
    $property_agentProfileDisplay = $properties['displayAgentProfile'] ?? '';
    $property_agentName = $properties['name'] ?? '';
    $property_agentTelephone = $properties['telephone'] ?? '';
    $property_agentEmail = $properties['email'] ?? '';
    $property_agentPhoto = $properties['agentPhoto'] ?? '';

    // Extract Address Details
    $property_address = $properties['address'] ?? [];
    $property_subNumber = $properties['subNumber'] ?? '';
    $property_streetNumber = $properties['streetNumber'] ?? '';
    $property_street = $properties['street'] ?? '';
    $property_suburb = $properties['suburb'] ?? '';
    $property_state = $properties['state'] ?? '';
    $property_postcode = $properties['postcode'] ?? '';
    $property_country = $properties['country'] ?? '';

    // Extract Category
    $property_category = $properties['category'] ?? '';

    // Extract Date Available
    $property_dateAvailable = $properties['dateAvailable'] ?? '';

    // Extract Rent and Bond
    $property_rent = $properties['rent'] ?? '';
    $property_bond = $properties['bond'] ?? '';

    // Extract Headline and Description
    $property_headline = $properties['headline'] ?? '';
    $property_description = $properties['description'] ?? '';

    // Extract Land and Building Details
    $property_landDetails = $properties['landDetails'] ?? [];
    $property_landAreaUnit = $properties['areaUnit'] ?? '';

    $property_buildingDetails = $properties['buildingDetails'] ?? [];
    $property_buildingArea = $properties['area'] ?? '';

    // Extract Features
    $property_features = $properties['features'] ?? [];
    $property_bedrooms = $properties['bedrooms'] ?? 0;
    $property_bathrooms = $properties['bathrooms'] ?? 0;
    $property_ensuite = $properties['ensuite'] ?? 0;
    $property_garages = $properties['garages'] ?? 0;
    $property_balcony = $properties['balcony'] ?? 0;
    $property_builtInRobes = $properties['builtinRobes'] ?? 0;
    $property_dishwasher = $properties['dishwasher'] ?? 0;
    $property_ductedHeating = $properties['ductedHeating'] ?? 0;
    $property_ductedCooling = $properties['ductedCooling'] ?? 0;
    $property_intercom = $properties['intercom'] ?? 0;
    $property_poolInGround = $properties['poolInGround'] ?? 0;

    // Extract Allowances
    $property_allowances = $properties['allowances'] ?? [];
    $property_petFriendly = $properties['petFriendly'] ?? 'false';
    $property_smokers = $properties['smokers'] ?? 'false';
    $property_furnished = $properties['furnished'] ?? 'false';

    // Extract Images
    $image_url = $properties['objects']['img'][0]['@attributes']['url'] ?? [];
    
    
    $image_urls = [];
    if (isset($property_images['image']) && is_array($property_images['image'])) {
        foreach ($property_images['image'] as $image) {
            $image_urls[] = $image['url'] ?? '';
        }
    }
    

    

    // Define unique ID for the property
    $unique_id = $property_uniqueID; // assuming this is the unique ID from your data

    // Check if the property with this unique ID already exists
    $args = array(
        'post_type' => 'property',
        'meta_query' => array(
            array(
                'key' => '_property_uniqueID',
                'value' => $unique_id,
                'compare' => '='
            ),
        ),
    );

    $existing_property = new WP_Query($args);

    if ($existing_property->have_posts()) {
        // Property exists, update it
        $existing_property->the_post();
        $property_id = get_the_ID();
    } else {
        // Property does not exist, create a new one
        $property_id = wp_insert_post(array(
            'post_title' => $property_headline,
            'post_content' => $property_description,
            'post_status' => 'publish',
            'post_type' => 'property',
        ));
    }

    // Insert or Update Property Attributes and Other Meta Fields
    update_post_meta($property_id, '_property_attributes', json_encode($property_attributes));
    update_post_meta($property_id, '_property_status', $property_status);
    update_post_meta($property_id, '_property_uniqueID', $property_uniqueID);
    update_post_meta($property_id, '_property_agentID', $property_agentID);
    update_post_meta($property_id, '_property_listingID', $property_listingID);
    update_post_meta($property_id, '_property_depositTaken', $property_depositTaken);
    update_post_meta($property_id, '_property_agentProfileDisplay', $property_agentProfileDisplay);
    update_post_meta($property_id, '_property_agentName', $property_agentName);
    update_post_meta($property_id, '_property_agentTelephone', $property_agentTelephone);
    update_post_meta($property_id, '_property_agentEmail', $property_agentEmail);
    update_post_meta($property_id, '_property_agentPhoto', $property_agentPhoto);
    update_post_meta($property_id, '_property_subNumber', $property_subNumber);
    update_post_meta($property_id, '_property_streetNumber', $property_streetNumber);
    update_post_meta($property_id, '_property_street', $property_street);
    update_post_meta($property_id, '_property_suburb', $property_suburb);
    update_post_meta($property_id, '_property_state', $property_state);
    update_post_meta($property_id, '_property_postcode', $property_postcode);
    update_post_meta($property_id, '_property_country', $property_country);
    update_post_meta($property_id, '_property_category', $property_category);
    update_post_meta($property_id, '_property_dateAvailable', $property_dateAvailable);
    update_post_meta($property_id, '_property_rent', $property_rent);
    update_post_meta($property_id, '_property_bond', $property_bond);
    update_post_meta($property_id, '_property_headline', $property_headline);
    update_post_meta($property_id, '_property_description', $property_description);
    update_post_meta($property_id, '_property_landDetails', json_encode($property_landDetails));
    update_post_meta($property_id, '_property_landAreaUnit', $property_landAreaUnit);
    update_post_meta($property_id, '_property_buildingDetails', json_encode($property_buildingDetails));
    update_post_meta($property_id, '_property_buildingArea', $property_buildingArea);
    update_post_meta($property_id, '_property_bedrooms', $property_bedrooms);
    update_post_meta($property_id, '_property_bathrooms', $property_bathrooms);
    update_post_meta($property_id, '_property_ensuite', $property_ensuite);
    update_post_meta($property_id, '_property_garages', $property_garages);
    update_post_meta($property_id, '_property_balcony', $property_balcony);
    update_post_meta($property_id, '_property_builtInRobes', $property_builtInRobes);
    update_post_meta($property_id, '_property_dishwasher', $property_dishwasher);
    update_post_meta($property_id, '_property_ductedHeating', $property_ductedHeating);
    update_post_meta($property_id, '_property_ductedCooling', $property_ductedCooling);
    update_post_meta($property_id, '_property_intercom', $property_intercom);
    update_post_meta($property_id, '_property_poolInGround', $property_poolInGround);
    update_post_meta($property_id, '_property_petFriendly', $property_petFriendly);
    update_post_meta($property_id, '_property_smokers', $property_smokers);
    update_post_meta($property_id, '_property_furnished', $property_furnished);

    // if (!empty($image_urls)) {
    //     update_post_meta($property_id, '_property_images', $image_urls);
    // }

    // // add property thumbnail
    // if (!empty($image_url)) {
    //     add_property_thumbnail($property_id, $image_url);
    // }

    set_featured_image_for_product($property_id, $image_url);

    // Update the status of the processed Property in your database
            $wpdb->update(
                $table_name,
                ['status' => 'completed'],
                ['id' => $serial_id]
            );
    // Reset post data
    wp_reset_postdata();

 return "Property imported successfully.";
}

// Set wocommerce Product Thumbnail function
function set_featured_image_for_product($property_id, $image_url) {
    // Check if image URL is not empty
    if (empty($image_url)) {
        echo 'Error: Image URL is empty.';
        return false; // Indicate failure
    }

    // Extract image name from URL
    $image_name = basename($image_url);

    // Get WordPress upload directory
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'];
    $upload_url = $upload_dir['url'];

    // Download the image from URL and save it to the upload directory
    $image_data = file_get_contents($image_url);

    if ($image_data !== false) {
        $image_file = $upload_path . '/' . $image_name;
        file_put_contents($image_file, $image_data);

        // Prepare image data to be attached to the product
        $file_path = $upload_path . '/' . $image_name;
        $file_name = basename($file_path);

        // Insert the image as an attachment
        $attachment = [
            'post_mime_type' => mime_content_type($file_path),
            'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        // Insert the attachment into the WordPress media library
        $attach_id = wp_insert_attachment($attachment, $file_path, $property_id);

        if (!is_wp_error($attach_id)) {
            // Generate attachment metadata
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attach_id, $file_path);
            wp_update_attachment_metadata($attach_id, $attachment_data);

            // Set the attachment as the product's featured image
            set_post_thumbnail($property_id, $attach_id);


            return true; // Indicate success
        } else {
            // Handle errors
            echo 'Error inserting attachment: ' . $attach_id->get_error_message();
        }
    } else {
        echo 'Error downloading image.';
    }

    return false; // Indicate failure
}


