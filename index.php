<?php
$update = json_decode(file_get_contents('php://input'));

include 'func.php';


$BOT_TOKEN = "236804473:AAFSpqTh7SYvgA1eAygI_JmrT9elWRUTQUA";
$BOT_API_URL= "https://api.telegram.org/bot".$BOT_TOKEN."/";


$user_msg = $update->message->text;
$chat_id = $update->message->from->id;
$user_chat_id = $update->message->from->username;
$user_chat_fname = $update->message->from->first_name;
$user_chat_lname = $update->message->from->last_name;

$sql="INSERT INTO mem_mar(chat_id, user_chat_id, user_chat_fname, user_chat_lname)
VALUES('$chat_id', '$user_chat_id', '$user_chat_fname', '$user_chat_lname')";

search_user($chat_id, $sql);
update_user_chat_data($chat_id, $user_chat_id, $user_chat_fname, $user_chat_lname);
update_user_data($chat_id, $chat_id, "user_mem_id");
$last_comment = findLastCommand($chat_id);

$input_name="نام خود را وارد کنید :";
$input_email="ایمیل خود را وارد کنید";
// $input_num="در صورت تمایل شماره تلفن همراه خود را وارد کنید  در صورت عدم تمایل یه ثبت شماره گزینه ی بعدی را بزنید";
$input_num="شماره موبایل خود را وارد کنید";

if($user_msg=="/start"){
	$ar = array(
	'keyboard' => array(
	0=>array("ثبت نام")
	)
	);
	$ar = json_encode($ar);
	showmenu($chat_id ,  $user_msg , $ar);
	updateLastCommand($chat_id, "/start");
}
else if($last_comment=="/start" && $user_msg=="ثبت نام"){
	updateLastCommand($chat_id, "wait_name");
	$input_name = str_replace(" ","%20",$input_name);
	sendmsg($chat_id, $input_name);	
	echo $input_name;
	echo $input_email;
	echo $input_num;
}
else if($last_comment=="wait_name"){
	if (!preg_match("/^[a-zA-Z ]*$/",$user_msg))
	   {
	      	$nameErr = "error";
			updateLastCommand($chat_id, "wait_name");
	       	sendmsg($chat_id, $nameErr);
	   }
	else{
		updateLastCommand($chat_id, "wait_email");
		update_user_data($chat_id, $user_msg, "user_name");
		sendmsg($chat_id, $input_email);
	}
}
else if($last_comment=="wait_email"){
	if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$user_msg))
	   {
		    $emailErr = "error"; 
		    updateLastCommand($chat_id, "wait_email");
		    sendmsg($chat_id, $emailErr);
	   }
	else{
		updateLastCommand($chat_id, "wait_num");
		update_user_data($chat_id, $user_msg, "user_email");
		sendmsg($chat_id, $input_num);
	}	
}
else if($last_comment=="wait_num"){
	if (isset($user_msg) && !is_numeric($user_msg))
	   {
		    $emailErr = "error"; 
		    updateLastCommand($chat_id, "wait_num");
		    sendmsg($chat_id, $emailErr);
	   }
	   else{
		   	updateLastCommand($chat_id, "end");
			update_user_data($chat_id, $user_msg, "user_number");
			// sendmsg($chat_id, "TNX");
			$ar = array(
			'keyboard' => array(
			0=>array("راهنما")
			)
			);
			$ar = json_encode($ar);
			showmenu($chat_id ,  "TNX" , $ar);
	   }
}
else if($chat_id=="57807452"){
	$user_txt=strtolower($user_msg);
	if($user_txt=="icnt"){
		updateLastCommand("57807452", "inc_cont");
		sendmsg("57807452", "salam");
	}
	else if($last_comment=="inc_cont"){
		updateLastCommand("57807452", "end");
		$userCount=findUserData($user_txt, "user_count")+1;
		update_user_data($user_txt, $userCount, "user_count");
		sendmsg("57807452", $userCount);
		// sendmsg($user_txt, "1 member %0A tedade kol =".$userCount);
	}
	else if($user_txt=="dcnt"){
		updateLastCommand("57807452", "dec_cont");
		sendmsg("57807452", "salam");
	}
	else if($last_comment=="dec_cont"){
		updateLastCommand("57807452", "end");
		$userCount=findUserData($user_txt, "user_count")-1;
		update_user_data($user_txt, $userCount, "user_count");
		sendmsg("57807452", $userCount);
	}
	else if($user_txt=="imcnt"){
		updateLastCommand("57807452", "inc_mccont");
		sendmsg("57807452", "salam");
	}
	else if($last_comment=="inc_mccont"){
		updateLastCommand("57807452", "inc_mcont");
		update_user_data("57807452", $user_txt, "user_txt");
		sendmsg("57807452", "tedad?");
	}
	else if($last_comment=="inc_mcont"){
		updateLastCommand("57807452", "end");
		$user_id=findUserData("57807452", "user_txt");
		$userCount=findUserData($user_id, "user_mem_count")+$user_txt;
		update_user_data($user_id, $userCount, "user_mem_count");
		sendmsg("57807452", $userCount);
	}
	else if($user_txt=="dmcnt"){
		updateLastCommand("57807452", "dec_mccont");
		sendmsg("57807452", "salam");
	}
	else if($last_comment=="dec_mccont"){
		updateLastCommand("57807452", "dec_mcont");
		update_user_data("57807452", $user_txt, "user_txt");
		sendmsg("57807452", "tedad?");
	}
	else if($last_comment=="dec_mcont"){
		updateLastCommand("57807452", "end");
		$user_id=findUserData("57807452", "user_txt");
		$userCount=findUserData($user_id, "user_mem_count")-$user_txt;
		update_user_data($user_id, $userCount, "user_mem_count");
		sendmsg("57807452", $userCount);
	}
	
}
// updateLastCommand($chat_id, $user_msg);
// sendmsg($chat_id, $user_msg);

?>