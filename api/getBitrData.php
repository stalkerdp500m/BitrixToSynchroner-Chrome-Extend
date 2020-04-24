<?php


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

//$btrDealId =$data['btrDealId'];

$btrDealId='5891';// сделка для теста

if($btrDealId){
   include './btrConf.php';




$CONTACT_ID=BitrixGetContact($btrDealId);
$clintData=BitrixGetclintData($CONTACT_ID);

header('Access-Control-Allow-Origin: *');
echo json_encode($clintData);

}


function BitrixGetContact($btrDealId)
{
    global $HOOKBIT;



    $ID = preg_replace('/\D/', '', $btrDealId);

    $method = 'crm.deal.get';

    $queryUrl = $HOOKBIT . $method;


    $queryData = http_build_query(array(
        'id' => strval($ID),

    ));
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));
    $result = curl_exec($curl);

    curl_close($curl);


    $otvet = json_decode($result, true);


    if (json_last_error() === JSON_ERROR_NONE) {

        if (array_key_exists('result', $otvet)) {
         $CONTACT_ID = $otvet["result"]["CONTACT_ID"];
            $status = 'true';
        } else {
            $status = 'false';
        }


        return $CONTACT_ID ;
    } else return array(
        'status' => 'false',
        'oshibGetnameJSON' => 'err'
    );
}



function BitrixGetclintData($CONTACT_ID){

   global $HOOKBIT;

       $ID = preg_replace('/\D/', '', $CONTACT_ID);

       $method = 'crm.contact.get';
     

       $queryUrl = $HOOKBIT . $method;

       $queryData = http_build_query(array(
           'id' => strval($ID),

       ));
       $curl = curl_init();
       curl_setopt_array($curl, array(
           CURLOPT_CONNECTTIMEOUT => 5,
           CURLOPT_SSL_VERIFYPEER => 0,
           CURLOPT_POST => 1,
           CURLOPT_HEADER => 0,
           CURLOPT_RETURNTRANSFER => 1,
           CURLOPT_URL => $queryUrl,
           CURLOPT_POSTFIELDS => $queryData,
       ));
       $result = curl_exec($curl);

       curl_close($curl);

       $otvet = json_decode($result, true);


       if (json_last_error() === JSON_ERROR_NONE) {

           if (array_key_exists('result', $otvet)) {

               $name = $otvet["result"]["NAME"];
               $surname=$otvet["result"]["LAST_NAME"];
               $nip = $otvet["result"]['UF_CRM_5B0FF4D6D4E68'];//inn
$pasport=$otvet["result"]['UF_CRM_5BF3DA4127727'];

               $identitySeries=preg_replace( "/[^a-zA-ZА-Яа-я\s]/", '', $pasport );
               $identityNumber=preg_replace( "/[^0-9\s]/", '', $pasport );
               $cellPhone = $otvet["result"]['PHONE'][0]['VALUE'];
         
                $dateOfBirth = date("d.m.Y", strtotime($otvet["result"]['BIRTHDATE']));//преобразую д/р
               $placeOfBirth = $otvet["result"]['UF_CRM_5AF80BBF3046B'];
               
$parentnames = preg_split("/[\s,]+/",$otvet["result"]['UF_CRM_5B0FF4D6DC068']);
               if($parentnames[1]){
               $fathersName=$parentnames[1];}
               else{$fathersName='';}

               if($parentnames[0]){
               $mothersName=$parentnames[0];}
               else{$mothersName='';}


         $height= $otvet["result"]['UF_CRM_5B0FF4D716A1E'][0];   //множественное поле, берем 0 елемент  
         $shirtSize = $otvet["result"]['UF_CRM_5B1676D298116'];
         $shoeSize=$otvet["result"]['UF_CRM_5B0FF4D70E970'][0];   //снова множественное


           } else {
               $status = 'false';
               $nameClint = " ";
           }



           $clintData=array(
            'name'=>$name, 
            'surname'=>$surname, 
            'nip'=>$nip,
            'identitySeries'=>$identitySeries, 
            'identityNumber'=>$identityNumber,
            'cellPhone'=> $cellPhone, 
            'dateOfBirth'=>$dateOfBirth,
            'placeOfBirth'=>$placeOfBirth, 
            'fathersName'=>$fathersName,
            'mothersName'=>$mothersName,
            'height'=>$height, 
            'shirtSize'=>$shirtSize,
            'shoeSize'=> $shoeSize, 
            'contactPhone'=>$cellPhone
         );


           return $clintData;
       } else return array(
           'status' => 'false'
       );
   

}