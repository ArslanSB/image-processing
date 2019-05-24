<?php
    if(isset($_POST['canvasImage'])){
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['canvasImage']));
        file_put_contents('./uploads/uploaded_'. $_POST['prefix'] .'.png', $data);

        $post_data = array('fileName' => 'uploaded_'. $_POST['prefix'] .'.png');
        $post_data = json_encode($post_data);

        $curl = curl_init("http://im_tensorflow:5000/handwritting_cnn");
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
?>
