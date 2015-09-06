<?php
if(!defined('vendors'))
  die('Direct access not permitted');

define('IN_MYBB', 1);
require_once "C:/xampp/htdocs/mybbforum/global.php";
$lang->load("member");

function check_register($username, $password, $email) {
	global $mybb, $user_info, $db, $lang;
	if($mybb->settings['disableregs'] == 1) {
		error($lang->registrations_disabled);
	}
	if($mybb->user['uid'] != 0)
	{
		error($lang->error_alreadyregistered);
	}
	if($mybb->settings['betweenregstime'] && $mybb->settings['maxregsbetweentime'])
	{
		$time = TIME_NOW;
		$datecut = $time-(60*60*$mybb->settings['betweenregstime']);
		$query = $db->simple_select("users", "*", "regip=".$db->escape_binary($session->packedip)." AND regdate > '$datecut'");
		$regcount = $db->num_rows($query);
		if($regcount >= $mybb->settings['maxregsbetweentime'])
		{
			$lang->error_alreadyregisteredtime = $lang->sprintf($lang->error_alreadyregisteredtime, $regcount, $mybb->settings['betweenregstime']);
			error($lang->error_alreadyregisteredtime);
		}
	}
	$usergroup = 2;
	require_once MYBB_ROOT."inc/datahandlers/user.php";
	$userhandler = new UserDataHandler("insert");
	$coppauser = 0;
	if(isset($mybb->cookies['coppauser']))
	{
		$coppauser = (int)$mybb->cookies['coppauser'];
	}

	// Set the data for the new user.
	$user = array(
		"username" => $username,
		"password" => $password,
		"password2" => $password,
		"email" => $email,
		"email2" => $email,
		"usergroup" => $usergroup,
		"regip" => $session->packedip,
		"registration" => true
	);
	if(isset($mybb->cookies['coppadob']))
	{
		list($dob_day, $dob_month, $dob_year) = explode("-", $mybb->cookies['coppadob']);
		$user['birthday'] = array(
			"day" => $dob_day,
			"month" => $dob_month,
			"year" => $dob_year
		);
	}

	$user['options'] = array(
		"allownotices" => '',
		"hideemail" => '',
		"subscriptionmethod" => '',
		"receivepms" => '',
		"pmnotice" => '',
		"pmnotify" => '',
		"invisible" => '',
		"dstcorrection" => ''
	);
	$userhandler->set_data($user);
	$errors = "";
	if(!$userhandler->validate_user())
	{
		$errors = $userhandler->get_friendly_errors();
	}
	$user_info = $userhandler->insert_user();
	my_setcookie("mybbuser", $user_info['uid']."_".$user_info['loginkey'], null, true);
//	die(var_dump($mybb->cookies['mybbuser']));
//	redirect("pages/paid.php");
	return "btcaddress";
}

function grab_payment_details() {
	global $mybb, $user_info, $db, $lang;
	die(var_dump($mybb->cookies['mybbuser']));
}



?>
