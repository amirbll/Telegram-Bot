<?php

function DB_conn(){
    $host="localhost";
    $username="root";
    $password="";
    $DBname="memMarket";
    $DB_con=mysqli_connect($host, $username, $password, $DBname);
    if(!$DB_con){
        die("Connection failed: ".mysqli_connect_error());
    }

    return $DB_con;
}

function sendmsg($chat_id , $message_des){
	$BOT_TOKEN = "236804473:AAFSpqTh7SYvgA1eAygI_JmrT9elWRUTQUA";
	$BOT_API_URL= "https://api.telegram.org/bot".$BOT_TOKEN."/";
    file_get_contents($BOT_API_URL."sendmessage?chat_id=".$chat_id."&text=".$message_des);
}

function showmenu($chat_id , $message_des, $array){
    $BOT_TOKEN = "236804473:AAFSpqTh7SYvgA1eAygI_JmrT9elWRUTQUA";
    $BOT_API_URL= "https://api.telegram.org/bot".$BOT_TOKEN."/";
	file_get_contents($BOT_API_URL."sendmessage?chat_id=".$chat_id."&text=".$message_des."&reply_markup=".$array);
}

function search_user($chat_id, $sqli){
    
    $DB_con=DB_conn();

	$sqls = "SELECT chat_id FROM mem_mar";
	$result = mysqli_query($DB_con, $sqls);
    $cont=0;
	if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)) {
            if ($row["chat_id"]==$chat_id) {
                $cont=1;
            }
        }
        if($cont==0){
            mysqli_query($DB_con, $sqli); 
            $DB_update = "UPDATE mem_mar SET last_cmt='/start' WHERE chat_id=$chat_id";
            mysqli_query($DB_con, $DB_update);
        }

    } else {
        mysqli_query($DB_con, $sqli); 
    }
    mysqli_close($row);
    mysqli_close($result);
    mysqli_close($DB_con);
}

function findLastCommand($chat_id){
    
    $DB_con=DB_conn();

	$sqlse="SELECT last_cmt FROM mem_mar WHERE chat_id=$chat_id";
	$result=mysqli_query($DB_con, $sqlse);

	$command="";

	if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $command = $row["last_cmt"];
	}
	else {
        $command = "NOP";
    }

    mysqli_close($row);
    mysqli_close($result);
    mysqli_close($DB_con);
    return $command;
}

// function findUserCount($chat_id){
    
//     $DB_con=DB_conn();

//     $sqlse="SELECT user_count FROM mem_mar WHERE chat_id=$chat_id";
//     $result=mysqli_query($DB_con, $sqlse);

//     $command="";

//     if (mysqli_num_rows($result) > 0){
//         $row = mysqli_fetch_assoc($result);
//         $command = $row["user_count"];
//     }
//     else {
//         $command = "NOP";
//     }

//     mysqli_close($row);
//     mysqli_close($result);
//     mysqli_close($DB_con);
//     return $command;
// }

function findUserData($chat_id, $user_data_type){
    
    $DB_con=DB_conn();

    $sqlse="SELECT $user_data_type FROM mem_mar WHERE chat_id=$chat_id";
    $result=mysqli_query($DB_con, $sqlse);

    $command="";

    if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $command = $row["$user_data_type"];
    }
    else {
        $command = "NOP";
    }

    mysqli_close($row);
    mysqli_close($result);
    mysqli_close($DB_con);
    return $command;
}

function updateLastCommand($chat_id, $new_com){
   
    $DB_con=DB_conn();

    $DB_update = "UPDATE mem_mar SET last_cmt='$new_com' WHERE chat_id=$chat_id";
    mysqli_query($DB_con, $DB_update);

    mysqli_close($DB_con);
}

function update_user_chat_data($chat_id, $user_chat_id, $user_chat_fname, $user_chat_lname){
    
    $DB_con=DB_conn();

    $sqls = "SELECT user_chat_id, user_chat_fname, user_chat_lname FROM mem_mar WHERE chat_id=$chat_id";
    $result = mysqli_query($DB_con, $sqls);
    if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        if($row["user_chat_id"]!==$user_chat_id){
            $DB_update = "UPDATE mem_mar SET user_chat_id='$user_chat_id' WHERE chat_id=$chat_id";
            mysqli_query($DB_con, $DB_update);
        }
        if($row["user_chat_fname"]!==$user_chat_id){
            $DB_update = "UPDATE mem_mar SET user_chat_fname='$user_chat_fname' WHERE chat_id=$chat_id";
            mysqli_query($DB_con, $DB_update);
        }
        if($row["user_chat_lname"]!==$user_chat_lname){
            $DB_update = "UPDATE mem_mar SET user_chat_lname='$user_chat_lname' WHERE chat_id=$chat_id";
            mysqli_query($DB_con, $DB_update);
        }
    }

    mysqli_close($row);
    mysqli_close($result);
    mysqli_close($DB_con);
}

function update_user_data($chat_id, $user_msg, $user_data_type){
    
    $DB_con=DB_conn();

    $DB_update = "UPDATE mem_mar SET $user_data_type='$user_msg' WHERE chat_id=$chat_id";
    mysqli_query($DB_con, $DB_update);

    mysqli_close($DB_con);
}

?>