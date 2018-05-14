
<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/log.txt');
error_reporting(E_All);
    include("Messenger.php");
    $access_token = "EAACVhaMuOqQBAFOCN7j2yLldomMKls22QRiodLidvigUvUfmRU29MV8lGDlBtMne0CHpjV7nkw8zWS75lcdGTCbN37HG1rHTVHjFlANZARKO0w9JeIJyWmaHaScCdbXyIbBDAeKQzbdLo7pknRToaH1d8QuasDOIgvDNANAZDZD";
    $apiKey = $access_token;
    
    // Instances the Facebook class
    $facebook = new Messenger($apiKey);


    // Take text and chat_id from the message
    $text = $facebook->Text();
    $chat_id = $facebook->ChatID();
    // $message_id = $facebook->EntryID();

    $message = "";
    $result = "";
 //                    'image_url' => '\https://www.dropbox.com/s/rmrgytikwhxvy14/repainterLogo.png?dl=0',

   
 function sendStoreLogo($facebook,$chat_id){

 
            $elements = array(
                array(
                    'title' => 'Hyde Park Produce',
                    'item_url' => '',
                    'image_url' => 'https://scontent-mxp1-1.xx.fbcdn.net/v/t1.0-9/13007379_1549375528696077_3770912868944141667_n.jpg?_nc_cat=0&oh=973b911ec89410ce50992437884b7a07&oe=5B908005',
                    'subtitle' => ''
                )
            );
            $facebook->sendGenericTemplate($chat_id, $elements);


    }
 
   
    function sendGenericMessage($facebook,$chat_id){

   $button = array(
                array(
                  'type' => 'postback',
                    'title' => 'Redeem',
                    'payload' => 'redeem_pressed'
                )
            );
            $elements = array(
                array(
                    'title' => 'Tap the Redeem Button',
                    'item_url' => '',
                    'image_url' => 'https://image.ibb.co/nmCAEG/reward_message.png',
                    'subtitle' => '',
                    'buttons' => $button
                )
            );
            $facebook->sendGenericTemplate($chat_id, $elements);

    }

$store_name_for_msg="Hyde Park Produce";
$store_name="Hyde%20Park%20Produce";
$points=5;
$rewardFlag=false;
/**
 * Webhook for Time Bot- Facebook Messenger Bot
 */
// Your Page Access Token
$access_token = "EAACVhaMuOqQBAFOCN7j2yLldomMKls22QRiodLidvigUvUfmRU29MV8lGDlBtMne0CHpjV7nkw8zWS75lcdGTCbN37HG1rHTVHjFlANZARKO0w9JeIJyWmaHaScCdbXyIbBDAeKQzbdLo7pknRToaH1d8QuasDOIgvDNANAZDZD";



// Your webhook varification token
$verify_token = "lydia";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
 error_log(print_r("whole input",true));

error_log(print_r($input,true));

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$referral = $input['entry'][0]['messaging'][0]['referral'];
$redeemPayload=NULL;



if($input['entry'][0]['messaging'][0]['postback']['title'] == "Get Started"){

   
             $userName=getName($sender,$access_token);
//New user inserted
             $userStatus=sendShopperInfo($sender,$access_token);
          //$facebook->sendMessage("2111480812199546", $userName." pressed get stated. the user status in db was :  ".$userStatus);

              if($userStatus=="new"){ 


              $facebook->sendMessage($chat_id, "Welcome ".$userName." to the Repainter rewards program at ".$store_name_for_msg."!");
             $returnedPoints=updatePoints($sender,$store_name,$points,"store");
             if(ctype_digit($returnedPoints)){



              $facebook->sendMessage($chat_id, "You have 5 points! Show the pic below to the cashier to pick up one free reward bag and double your points.");
              sendStoreLogo($facebook,$chat_id);
}
               if($returnedPoints=='storeLimit'){
                         $facebook->sendMessage($chat_id,"You have already scanned the store code for today, please try again in 24 hours.");


           }


             }
             if($userStatus=="exists"){
                       $facebook->sendMessage($chat_id, "Welcome back! ".$userName." Please Scan The Store Code.");


                 // sendGenericMessage();

             }




}


if($input['entry'][0]['messaging'][0]['postback']['title'] == "Redeem"){
      error_log(print_r("postback titl: ".$input['entry'][0]['messaging'][0]['postback']['title'],true));



$redeemPayload=$input['entry'][0]['messaging'][0]['postback']['title'];
  $redeemMsg=redeemPoints($sender,$store_name,$points);
  if($redeemMsg=='redeem'){
    // $facebook->sendMessage("2111480812199546", "yes bot was tested");
     $facebook->sendMessage($chat_id, "Please scan the store code to redeem.");
  }
  error_log(print_r("redem response".$redeemMsg,true));

}


error_log(print_r("only payload".$redeemPayload,true));

$message_to_reply = "nothing";

/**
 * Some Basic rules to validate incoming messages
 */

    $message_to_reply = 'Sorry, I don\'t understand you. I can only tell what time it is now.';
$data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);

if(!empty($referral = $input['entry'][0]['messaging'][0]['referral'])){
   
    if($referral['ref']=='rajun-cajun-store-code' ){
          // $facebook->sendMessage($chat_id, "This store code does not match the store you are currently in. Please try scanning the code at the register where you are.");
          $message_to_reply="This store code does not match the store you are currently in. Please try scanning the code at the register where you are.";


    }

     if($referral['ref']=='rajun-cajun-reward-code' ){
         //  $facebook->sendMessage($chat_id, "Great You're using your rewar does not earn you any points at this store, but you've still got all the other points");
             $message_to_reply="Great You're using your reward bag! This bag is from another store, so this does not earn you any points at this store, but you've still got all the other points";



    }


    if($referral['ref']!='hpp-store' && $referral['ref']!='hpp-bag'){
                 // $facebook->sendMessage($chat_id, "Sorry, but this code is not recognized. Please try again or try another code");
                $message_to_reply='Sorry, but this code is not recognized. Please try again or try another code.';

    }


    if($referral['ref']=='hpp-store'){


             $userName=getName($sender,$access_token);
             $userStatus=sendShopperInfo($sender,$access_token); //use it her so if the user is new and jsut scnaing so he can be firstr added.

             $returnedPoints=updatePoints($sender,$store_name,$points,"store");
             error_log(print_r("returnedPoints ".$returnedPoints,true));

             if($returnedPoints=='storeLimit'){
                           $message_to_reply="You’ve already scanned the store code. You can only scan the store code once per day.";


           }

             if($returnedPoints=='points are 100'){
                    // $facebook->sendMessage("2111480812199546", $userName." yes bot was tested 100 points");


                    
                //$message_to_reply='Congratulations '.$userName.'! You have earned our 100 points completion reward. Please show the below picture to store cashier and tap on redeem button blow.';
                  $message_to_reply='Congratulations '.$userName.' on 100 points! You have earned reward at '.$store_name_for_msg.'.';

                $rewardFlag=true;
                                 // sendGenericMessage($facebook,$chat_id);


             }
             if($returnedPoints=='redeemUnset'){
                    // $facebook->sendMessage("2111480812199546", $userName." yes bot was tested redeem unset");


                $message_to_reply='Congratulations '.$userName.'! You have redeemed the reward successfully';


             }

             if(ctype_digit($returnedPoints)){
                    // $facebook->sendMessage("2111480812199546", $userName." yes bot was tested 5 points added and double it  ".$returnedPoints);


             $message_to_reply='Welcome back '.$userName.'! You’ve got 5 more points at Hyde Park Produce! Double it by scanning your reward bag code. Your Total Points are '.$returnedPoints.".";
         }
}



    if($referral['ref']=='hpp-bag'){

             $userName=getName($sender,$access_token);
              $userStatus=sendShopperInfo($sender,$access_token); //use it her so if the user is new and jsut scnaing so he can be firstr added.

            
               $returnedPoints=updatePoints($sender,$store_name,$points,"bag");

               //bagtaken
                if($returnedPoints=='bagLimit'){
                                    // $facebook->sendMessage("2111480812199546", $userName." yes bot was tested 100 points reward");

                $message_to_reply='Your bag code must be scanned within 60 minutes of scanning the store code. Please scan in for points on your next trip to '.$store_name_for_msg.'.';
            
        }

                    if($returnedPoints=='bagtaken'){
                                    // $facebook->sendMessage("2111480812199546", $userName." yes bot was tested 100 points reward");

                $message_to_reply='You’ve already scanned your bag code. You’ll be able to scan in starting tomorrow.';
              
        }

               if($returnedPoints=='points are 100'){
                                   //  $facebook->sendMessage("2111480812199546", $userName." yes bot was tested 100 points reward");

                $message_to_reply='Congratulations '.$userName.'! You have earned our 100 points completion reward';
                $rewardFlag=true;
                                 // sendGenericMessage($facebook,$chat_id);


             }

             elseif($returnedPoints=='redeemUnset'){
                  //   $facebook->sendMessage("2111480812199546", $userName." yes bot was tested redeem unset");


                $message_to_reply='Congratulations '.$userName.'! You have redeemed the reward successfully';
                                

             }
           if(ctype_digit($returnedPoints)){
                                   //  $facebook->sendMessage("2111480812199546", $userName." yes bot was tested for reward bag".$returnedPoints);

            $message_to_reply='Congrats '.$userName.', your points are doubled! You have '.$returnedPoints." points at ".$store_name_for_msg."! 100 points earn you reward." ;
            }

}

}
if (!empty($data['entry'][0]['messaging'])) { 

        foreach ($data['entry'][0]['messaging'] as $message) { 


        $command = "";

        // When bot receive message from user
        if (!empty($message['message'])) {
             $command = $message['message']['text']; 
   
             $userName=getName($sender,$access_token);
//New user inserted
             $userStatus=sendShopperInfo($sender,$access_token);
        //  $facebook->sendMessage("2111480812199546", $userName." said ".$message['message']['text']);

              if($userStatus=="new"){

                           $message_to_reply="Hello! ".$userName." Please Scan The Store Code.";


             }
             if($userStatus=="exists"){
                 $message_to_reply="Welcome back! ".$userName." Please Scan The Store Code.";
                      


                 // sendGenericMessage();

             }
            //  $get_data = callAPI('GET', 'http://repainter.io/showpoints.php?meid=21&storecode=hpp-store', false);
            // $response = json_decode($get_data, true);
            // $errors = $response['response']['errors'];
            // $data = $response['response']['data'][0];
            // error_log(print_r("Test Get ".$response[messages][0][text],true));
 


                     
        }
       
    }
}

//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}

else if(!empty($input['entry'][0]['messaging'][0]['referral'])){
    $result = curl_exec($ch);
    if($rewardFlag){

                                          sendGenericMessage($facebook,$chat_id);

    }
}

///////////////////////////
function getName($userId,$access_token){
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.6/'.$userId.'?fields=first_name,last_name,profile_pic,locale,timezone,gender&access_token='.$access_token);
$result = curl_exec($ch);
curl_close($ch);

 $obj = json_decode($result,true); // *** here

// echo 'Hi ' . $obj['first_name'] . ' ' . $obj['last_name']
error_log(print_r("curl".$obj['locale']."  ".$obj['gender'],true));
return $obj['first_name'];
}

function sendShopperInfo($userId,$access_token){
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.6/'.$userId.'?fields=first_name,last_name,profile_pic,locale,timezone,gender&access_token='.$access_token);
$result = curl_exec($ch);
curl_close($ch);

 $obj = json_decode($result,true); // *** here

// echo 'Hi ' . $obj['first_name'] . ' ' . $obj['last_name']
error_log(print_r("curl".$obj['locale']."  ".$obj['first_name']."  ".$obj['last_name']."  ".$obj['gender']."  ".$userId."  ".$obj['timezone']."  ".$obj['gender'],true));
error_log(print_r('URL CODEING  http://repainter.io/showpoints.php?messenger_id='.$userId.'&first_name='.$obj['first_name'].'&last_name='.$obj['last_name'].'&locale='.$obj['locale'].'&timezone='.$obj['timezone'].'&gender='.$obj['gender'].'&goodie_points=0',true));

//'http://repainter.io/showpoints.php?messenger_id='.$userId.'&first_name='.$obj['first_name'].'&last_name='.$obj['last_name'].'&locale='.$obj['locale'].'&timezone='.$obj['timezone'].'&gender='.$obj['gender'].'&goodie_points=0'
// return $obj['first_name'];
// messenger_id,first_name,last_name,locale,timezone,gender,goodie_points,registered_at
// http://repainter.io/showpoints.php?messenger_id=2111480812199546&first_name=Muhammad&last_name=jan&locale=en_GB&timezone=5&gender=male&goodie_points=0
 $get_data = callAPI('GET', 'http://repainter.io/showpoints.php?messenger_id='.$userId.'&first_name='.$obj['first_name'].'&last_name='.$obj['last_name'].'&locale='.$obj['locale'].'&timezone='.$obj['timezone'].'&gender='.$obj['gender'].'&goodie_points=0', false);
            $response = json_decode($get_data, true);
            $errors = $response['response']['errors'];
            $data = $response['response']['data'][0];
            error_log(print_r("getshopperInfo :  ".$response[messages][0][text],true));
            error_log(print_r("Test error get ".$errors,true));

return $response[messages][0][text];
}




function updatePoints($userId,$store_name,$points,$code_type){
                 error_log(print_r("vars ".$userId." - ".$store_name." -".$points,true));

     $get_data = callAPI('GET', 'http://repainter.io/addpoint.php?messenger_id='.$userId.'&store_name='.$store_name.'&points='.$points.'&code_type='.$code_type, false);
                                 http://repainter.io/addpoint.php?messenger_id=007007&store_name=Hyde%20Park%20Produce&points=5
            $response = json_decode($get_data, true);
            $errors = $response['response']['errors'];
            $data = $response['response']['data'][0];
            error_log(print_r("update points :  ".$response[messages][0][text],true));
            error_log(print_r("Test error get ehy ".$errors,true));

return $response[messages][0][text];
}


function redeemPoints($userId,$store_name,$points){
                 error_log(print_r("vars ".$userId." - ".$store_name." -".$points,true));

     $get_data = callAPI('GET', 'http://repainter.io/redeem.php?messenger_id='.$userId.'&store_name='.$store_name.'&points='.$points, false);
                                 http://repainter.io/addpoint.php?messenger_id=007007&store_name=Hyde%20Park%20Produce&points=5
            $response = json_decode($get_data, true);
            $errors = $response['response']['errors'];
            $data = $response['response']['data'][0];
            error_log(print_r("Test Get why ".$response[messages][0][text],true));
            error_log(print_r("Test error get ehy ".$errors,true));

return $response[messages][0][text];
}




////////////////



function callAPI($method, $url, $data){
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: 111111111111111111111',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}


/////////////















?>
