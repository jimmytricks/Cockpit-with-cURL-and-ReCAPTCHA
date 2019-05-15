<?php 
    // reCaptcha info
    $secret = "your_secret_key";
    $remoteip = $_SERVER["REMOTE_ADDR"];
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $response = $_POST["g-recaptcha-response"];

    // Curl Request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'secret' => $secret,
        'response' => $response,
        'remoteip' => $remoteip
        ));
    $curlData = curl_exec($curl);
    curl_close($curl);

    $recaptcha = json_decode($curlData, true); 
    if ($recaptcha["success"]) {

        $data = array(
            'form' => array(
            'first_name' => $_POST["first_name"],
            'last_name' => $_POST["last_name"],
            'company' => $_POST["company"],
            'city' => $_POST["city"],
            'state' => $_POST["state"],
            'phone' => $_POST["phone"],
            'email' => $_POST["email"],
            )
        );
         
        $jsondata = json_encode($data);
         
        // Prepare new cURL resource
        $ch = curl_init('https://your_domain.com/api/forms/submit/name_of_cockpit_form');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
         
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Cockpit-Token: your_cockpit_token')
        );
         
        // Submit the POST request
        $result = curl_exec($ch);
        
        // Close cURL session handle
        curl_close($ch);

        header("Location: /contact/thank-you");
        exit();
    }
    else {
        echo "You did not pass the spam test and are most likely a robot, or are trying to access this page outside of using Google recaptcha. If this is a mistake please try resubmitting the form again.";
    }

    ?>
