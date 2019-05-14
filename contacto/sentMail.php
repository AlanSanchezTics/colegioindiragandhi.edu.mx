<?php
        $my_email = "admin@ciaigandhi.com";
        $continue = "/";
        $errors = array();
        if(count($_COOKIE)){
            foreach(array_keys($_COOKIE) as $value){
                unset($_REQUEST[$value]);
            }
        }
    
        if(isset($_REQUEST['email']) && !empty($_REQUEST['email'])){
            $_REQUEST['email'] = trim($_REQUEST['email']);
            if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){
                    $errors[] = "Correo Electronico Invalido";
            }else{
                $exploded_email = explode("@",$_REQUEST['email']);
                if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){
                    $errors[] = "Correo Electronico Invalido";
                }else{
                    if(substr_count($exploded_email[1],".") == 0){
                        $errors[] = "Correo Electronico Invalido";
                    }else{
                        $exploded_domain = explode(".",$exploded_email[1]);
                        if(in_array("",$exploded_domain)){
                            $errors[] = "Correo Electronico Invalido";
                        }else{
                            foreach($exploded_domain as $value){
                                if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){
                                    $errors[] = "Correo Electronico Invalido"; break;
                                }
                            }
                        }
                    }
                }
            }
        }
    
        if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){
            $errors[] = "You must enable referrer logging to use the form";
        }
    
        function recursive_array_check_blank($element_value){
            global $set;
            if(!is_array($element_value)){
                if(!empty($element_value)){
                    $set = 1;
                }
            }else{
                foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}
            }
        }
    
        recursive_array_check_blank($_REQUEST);
        if(!$set){
            $errors[] = "You cannot send a blank form";
        }
        unset($set);
    
        if(count($errors)){
            exit;
        }
    
        if(!defined("PHP_EOL")){
            define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");
        }
    
        function build_message($request_input){
            if(!isset($message_output)){
                $message_output ="";
            }
            if(!is_array($request_input)){
                $message_output = $request_input;
            }else{
                foreach($request_input as $key => $value){
                    if(!empty($value)){
                        if(!is_numeric($key)){
                            $message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;
                        }else{
                            $message_output .= build_message($value).", ";
                        }
                    }
                }
            }
            return rtrim($message_output,", ");
        }
    
        $message = build_message($_REQUEST);
        $message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."";
        $message = stripslashes($message);
    
        $subject = "Solicitud";
        $subject = stripslashes($subject);
    
        $from_name = "";
        if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
            $from_name = stripslashes($_REQUEST['name']);
        }
    
        $headers = "From: {$from_name} <{$_REQUEST['email']}>";
    
        if(mail($my_email,$subject,$message,$headers)===TRUE){
            echo "1";
        }else{
            echo "0";
        }
    
?>