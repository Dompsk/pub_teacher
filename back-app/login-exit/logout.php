<?php
// Include the Supabase connection file
include($_SERVER['DOCUMENT_ROOT'] . "/condb.php");

session_start();

// Check if a login session exists before attempting to log out
if (isset($_SESSION["log_id"])) {
    $log_id = $_SESSION["log_id"];
    // Use PHP's time() function for a timestamp
    $logout_time = gmdate('Y-m-d\TH:i:s\Z'); 

    // Prepare data for the API call
    $data = ['logout_time' => $logout_time];

    // Use the updateSupabaseData function to update the logout time
    updateSupabaseData('login_log', $data, 'log_id', $log_id);
}

// Clear all session variables
session_unset();
// Destroy the session
session_destroy();

// Redirect the user back to the login page
header("Location: /front-app/ex-user.php");
exit();
?>