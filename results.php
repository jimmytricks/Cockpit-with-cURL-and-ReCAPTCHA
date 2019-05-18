<?php
// reCaptcha info
$secret   = "YOUR_SECRET_KEY";
$remoteip = $_SERVER["REMOTE_ADDR"];
$url      = "https://www.google.com/recaptcha/api/siteverify";
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
    
    // your form fields
    $data = array(
        'form' => array(
            'first_name' => $_POST["first_name"],
            'last_name' => $_POST["last_name"],
            'email' => $_POST["email"],
            'city' => $_POST["city"],
            'province' => $_POST["province"],
            'country' => $_POST["country"],
            'message' => $_POST["message"],
            'add_to_email_list' => $_POST["add_to_email_list"]
        )
    );
    
    $jsondata = json_encode($data);
    
    // Prepare new cURL 
    $ch = curl_init('https://forms.adnetcms.com/api/forms/submit/yourdomain_contact');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    
    // HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Cockpit-Token: COCKPIT_TOKEN'
    ));
    
    // Submit
    $result = curl_exec($ch);
    
    // Close cURL
    curl_close($ch);
    
    //Redirect to thank you / success page
    header("Location: /contact/thank-you");
    exit();
    
} else {

    // If not successful echo text or forward to unsuccessful page
    header("Location: /contact/unsuccessful");
    exit();
}

?>
