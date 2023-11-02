<?php
// TomTom API Key
$api_key = 'Ada38vkEHpV2qi6PmlotdfXGCSknwaoO';

// Address for which you want to check traffic
$address = '26 Karingal Avenue, Carlingford NSW';

if(isset($_POST['SubmitButton'])){
  $message = "Success! You entered: ".$input;
  $address = $_POST['inputText']; //get input text
}    

// Use the TomTom Search API to get coordinates from the address
$search_url = "https://api.tomtom.com/search/2/geocode/" . urlencode($address) . ".json?key=" . $api_key;

// Initialize cURL session for address to coordinates
$ch_search = curl_init($search_url);
curl_setopt($ch_search, CURLOPT_RETURNTRANSFER, true);
$response_search = curl_exec($ch_search);
curl_close($ch_search);
$data_search = json_decode($response_search, true);

if (!empty($data_search['results'])) {
    $lat = $data_search['results'][0]['position']['lat'];
    $long = $data_search['results'][0]['position']['lon'];

    $flow_url = "https://api.tomtom.com/traffic/services/4/flowSegmentData/relative/10/json?point=" . $lat . "," . $long . "&key=" . $api_key;
    
    $ch_flow = curl_init($flow_url);
    curl_setopt($ch_flow, CURLOPT_RETURNTRANSFER, true);
    $response_routing = curl_exec($ch_flow);
    curl_close($ch_flow);
    
    $data_routing = json_decode($response_routing, true);
    
    if (!empty($data_routing['flowSegmentData'])) {
      $traffic = $data_routing['flowSegmentData'];
    } else {
      echo "Error retrieving traffic data";
    }
} else {
    echo "Error retrieving coordinates for the address";
}

?>

<html>
<body>    
  <form action="" method="post">
    <input type="text" name="inputText"/>
    <input type="submit" name="SubmitButton" value="Enter an address" />
  </form>
  <h2>Showing result for "<?php echo $address; ?>"</h2>
  <p>NOTE: <code>currentSpeed</code> is the current average speed of the address & <code>freeFlowSpeed</code> is the average speed in ideal conditions</p>

  <pre><?php print_r(json_encode($data_routing['flowSegmentData'], JSON_PRETTY_PRINT)); ?></pre>



  <style>
    code {
      background: lightgray;
      padding: 2px 6px;
      border-radius: 3px;
    }
    pre {
      border: 1px solid;
      padding: 1rem;
    }
  </style>
</body>
</html>




