<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{

		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_type'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{

		extract($_POST);
		if (isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_alumnus_id'] > 0) {
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = " . $_SESSION['login_alumnus_id']);
				if ($bio->num_rows > 0) {
					foreach ($bio->fetch_array() as $key => $value) {
						if ($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if ($_SESSION['bio']['status'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);

		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		// echo $data;
		// if($type == 1)
		// 	$establishment_id = 0;
		// // $data .= ", establishment_id = '$establishment_id' ";
		$sql = "SELECT * FROM users Where username='" . $username . "'";
		$chk = $this->db->query($sql)->num_rows;
		echo $chk;
		if ($chk === 0) {
			$this->db->query("INSERT INTO users set " . $data);
			return 1;
		} elseif ($chk > 0 && $id != '') {
			$this->db->query("UPDATE users set " . $data . " where id = " . $id);
			return 1;
		} else {

			return 2;
		}




		// var_dump( $chk);
		// if($chk > 0){
		// 	echo "ab";
		// 	// return 2;
		// 	// exit;
		// }elseif(empty($id)){
		// 	echo "b";
		// 	$this->db->query("INSERT INTO users set ".$data);
		// 	return 1;
		// 	exit;
		// }else{
		// 	// echo "c";
		// 	// $save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		// 	// return 1;
		// 	// exit;
		// }
		// if($save){
		// 	return 1;
		// }
	}

	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$uid = $this->db->insert_id;
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if ($data) {
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}
	function update_account()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if ($save) {
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if ($data) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['settings'][$key] = $value;
			}

			return 1;
		}
	}


	function save_plan()
	{
		extract($_POST);
		$data = " plan = '$plan' ";
		$data .= ", amount = '$amount' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO plans set $data");
		} else {
			$save = $this->db->query("UPDATE plans set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_plan()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM plans where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_package()
	{
		extract($_POST);
		$data = " package = '$package' ";
		$data .= ", description = '$description' ";
		$data .= ", amount = '$amount' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO packages set $data");
		} else {
			$save = $this->db->query("UPDATE packages set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_package()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM packages where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_trainer()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", rate = '$rate' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO trainers set $data");
		} else {
			$save = $this->db->query("UPDATE trainers set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_trainer()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM trainers where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_member()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!empty($v)) {
				if (!in_array($k, array('id', 'member_id', 'lastname', 'firstname', 'middlename', 'email', 'contact', 'gender', 'address'))) {
					if (empty($data))
						echo	$data .= " $k='{$v}' ";
					else
						$data .= ", $k='{$v}' ";
				}
			}
		}
		if (empty($member_id)) {
			$i = 1;
			while ($i == 1) {
				$rand = mt_rand(1, 99999999);
				$rand = sprintf("%'08d", $rand);
				$chk = $this->db->query("SELECT * FROM members where member_id = '$rand' ")->num_rows;
				if ($chk <= 0) {
					$data .= ", member_id='$rand' ";
					$i = 0;
				}
			}
		}

		if (empty($id)) {
			if (!empty($member_id)) {
				$chk = $this->db->query("SELECT * FROM members where member_id = '$member_id' ")->num_rows;
				if ($chk > 0) {
					return 2;
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO members set $data ");
			if ($save) {
				$member_id = $this->db->insert_id;
				$data = " member_id ='$member_id' ";
				$data .= ", plan_id ='$plan_id' ";
				$data .= ", package_id ='$package_id' ";
				$data .= ", trainer_id ='$trainer_id' ";
				$data .= ", start_date ='" . date("Y-m-d") . "' ";
				$plan = $this->db->query("SELECT * FROM plans where id = $plan_id")->fetch_array()['plan'];
				$data .= ", end_date ='" . date("Y-m-d", strtotime(date('Y-m-d') . ' +' . $plan . ' months')) . "' ";
				$save = $this->db->query("INSERT INTO registration_info set $data");
				if (!$save)
					$this->db->query("DELETE FROM members where id = $member_id");
			}
		} else {
			if (!empty($member_id)) {
				$chk = $this->db->query("SELECT * FROM members where member_id = '$member_id' and id != $id ")->num_rows;
				if ($chk > 0) {
					return 2;
					exit;
				}
			}
			$save = $this->db->query("UPDATE members set $data where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_member()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM members where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function register_member()
	{
		if (isset($_POST['sbmt'])) {

			// ========================first name validation=====================


			if (isset($_POST['fname']) && $_POST['fname'] != "") {
				if (preg_match('/[0-9]/', $_POST['fname'])) {
					$firstNameError = 'No digits are allowed ';
					$err1 = true;
				} elseif (preg_match('/^[a-zA-Z]+$/', $_POST['fname'])) {

					$fname = $_POST['fname'];
					$firstNameError = "";
					$err1 = false;
				}
			} else {
				$firstNameError = "first name cannot be empty ";
				$err1 = true;
			}

			// ========================Middle name validation=====================


			if (isset($_POST['mname']) && $_POST['mname'] != "") {
				if (preg_match('/[0-9]/', $_POST['mname'])) {
					$firstNameError = 'No digits are allowed ';
					$err1 = true;
				} elseif (preg_match('/^[a-zA-Z]+$/', $_POST['fname'])) {

					$mname = $_POST['mname'];
					$firstNameError = "";
					$err1 = false;
				}
			} else {
				$firstNameError = "middle name cannot be empty ";
				$err1 = true;
			}



			// ========================last name validation=====================
			if (isset($_POST['lname']) && $_POST['lname'] != "") {

				if (preg_match('/[0-9]/', $_POST['lname'])) {
					$lastNameError = 'No digits are allowed';
					$err2 = true;
				} elseif (preg_match('/^[a-zA-Z]+$/', $_POST['lname'])) {

					$lname = $_POST['lname'];
					$err2 = false;
				}
			} else {
				echo "last name cannot be empty <br>";
				$err2 = true;
			}

			// ----------------email----------

			if (isset($_POST['mail'])) {
				if (preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $_POST['mail'])) {
					$sql = "SELECT email FROM member ";
					$result = $this->db->query($sql);
					print_r($result);
					foreach ($result as $res) {

						if ($res['email'] == $_POST['mail']) {
							$emailErr =  "email already exist ";
							$err3 = true;
							break;
						} else {

							$email = $_POST['mail'];
							$err3 = false;
							$emailErr = "";
							// echo "1 ";
						}
					}

					$email = $_POST['mail'];
				} else {
					$emailErr = "ivalid email";
					$err3 = true;
				}
			} else {
				$emailErr = "enter email ";

				$err3 = true;
			}

			// =======================date validation===========

			if (isset($_POST['dob'])) {
				$todaydate = date('Y-m-d');

				if ($_POST['dob'] >= $todaydate) {
					$dateErr = 'date is greater than today date <br>';
					$err4 = true;
				} else {

					$dateErr = "";
					$dob = $_POST['dob'];
					$err4 = false;
				}
			} else {
				$dateErr = "select a date <br>";
				$err4 = true;
			}

			// =============phone number validation============

			if (isset($_POST['phone_number'])) {
				if (preg_match('/\./', $_POST['phone_number'])) {
					$numberErr = 'dots are not allowed';
					$err5 = true;
				} else if (strlen($_POST['phone_number']) < 10 || strlen($_POST['phone_number']) > 10) {
					$numberErr = "digits are not equal to 10 <br>";
					$err5 = true;
				} else {

					$numberErr = "";
					$phone_number = $_POST['phone_number'];
					$err5 = false;
				}
			} else {
				$numberErr = "enter phone number <br>";
				$err = true;
			}

			// ======gender=======

			if (isset($_POST['gender'])) {

				$gender = $_POST['gender'];
				$genderErr = "";
				$err6 = false;
			} else {
				$genderErr = "select gender <br>";
				$err6 = true;
			}




			// ===============Address============

			if (isset($_POST['address']) && ($_POST['address']) != "") {

				$address = $_POST['address'];
				$addressErr = "";
				$err9 = false;
			} else {
				$addressErr = "enter address <br>";

				$err9 = true;
			}

			if ($err1 === false && $err2 === false && $err3 === false && $err4 === false && $err5 === false && $err6 === false &&   $err9 === false) {


				$sql = "INSERT INTO `members` ( `first_name`, `last_name`, `middle_name`,`dob`, `contact`, `gender`, `email`,  `address` ) VALUES ('$fname', '$lname','$mname',  '$dob', '$phone_number', '$gender', '$email',  '$address');";

				if ($this->db->query($sql) === true) {

					echo 'record inserted success';
					// header("Location: http://localhost/vaibhav/hunarr/display.php");
				}
			} else {

				echo "Form Cannot Submit";
			}
		}
	}

	function renew_membership()
	{
		extract($_POST);
		$prev = $this->db->query("SELECT * FROM registration_info where id = $rid")->fetch_array();
		$data = '';
		foreach ($prev as $k => $v) {
			if (!empty($v) && !is_numeric($k) && !in_array($k, array('id', 'start_date', 'end_date', 'date_created'))) {
				if (empty($data))
					$data .= " $k='{$v}' ";
				else
					$data .= ", $k='{$v}' ";
				$$k = $v;
			}
		}
		$data .= ", start_date ='" . date("Y-m-d") . "' ";
		$plan = $this->db->query("SELECT * FROM plans where id = $plan_id")->fetch_array()['plan'];
		$data .= ", end_date ='" . date("Y-m-d", strtotime(date('Y-m-d') . ' +' . $plan . ' months')) . "' ";
		$save = $this->db->query("INSERT INTO registration_info set $data");
		if ($save) {
			$id = $this->db->insert_id;
			$this->db->query("UPDATE registration_info set status = 0 where member_id = $member_id and id != $id ");
			return $id;
		}
	}
	function end_membership()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE registration_info set status = 0 where id = " . $rid);
		if ($update) {
			return 1;
		}
	}

	function save_membership()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!empty($v)) {
				if (empty($data))
					$data .= " $k='{$v}' ";
				else
					$data .= ", $k='{$v}' ";
				$$k = $v;
			}
		}
		$data .= ", start_date ='" . date("Y-m-d") . "' ";
		$plan = $this->db->query("SELECT * FROM plans where id = $plan_id")->fetch_array()['plan'];
		$data .= ", end_date ='" . date("Y-m-d", strtotime(date('Y-m-d') . ' +' . $plan . ' months')) . "' ";
		$save = $this->db->query("INSERT INTO registration_info set $data");
		if ($save) {
			$id = $this->db->insert_id;
			$this->db->query("UPDATE registration_info set status = 0 where member_id = $member_id and id != $id ");
			return 1;
		}
	}
}


$a = new Action();
$a->register_member();
