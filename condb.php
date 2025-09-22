<?php
// condb.php
$SUPABASE_URL = "https://YOUR_PROJECT.supabase.co"; 
$SUPABASE_KEY = "YOUR_API_KEY"; // ใส่ Supabase Key ของคุณ

/**
 * ดึงข้อมูล table จาก Supabase REST API
 * @param string $table ชื่อ table
 * @return array ของ rows
 */
function getSupabaseData($table) {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $SUPABASE_URL . "/rest/v1/" . $table . "?select=*");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if(!is_array($data)) {
        die("Error: Response is not an array. Check your table name or API key.<br>Response: " . htmlspecialchars($response));
    }

    return $data;
}
?>
