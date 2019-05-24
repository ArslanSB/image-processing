<?php

if(isset($_POST['correctValue'])){
    
    $post_data = array(
        'fileName' => 'uploaded_'. $_POST['prefix'] .'.png',
        "label" => $_POST['correctValue']
    );
    $post_data = json_encode($post_data);

    $curl = curl_init("http://im_tensorflow:5000/train_handwritten_cnn");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($post_data))                                                                       
    );
    $res = curl_exec($curl);
    curl_close($curl);

    echo $res;

}