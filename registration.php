<?php
include "./db_connect.php";


session_start();


if (isset($_SESSION['fname'])) {
    header("Location: display.php");
}


   
// ----------------creating database------------

// $sql= 'CREATE DATABASE vaibhav';
// if($conn->query($sql) === true){
//     echo 'done';
// }else{
//     echo 'not done' .$conn->error;
// }

// --------- creating tabel------------

// $sql = "CREATE TABLE registration(
//     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     first_name VARCHAR(20) NOT NULL,
//     last_name VARCHAR(20) NOT NULL,
//     date_of_birth DATE NOT NULL,
//     phone_number INT(10) NOT NULL,
//     gender CHAR(6),
//     email VARCHAR(50) NOT NULL,
//     pass VARCHAR(10) NOT NULL,
//     qualification VARCHAR(10),
//     address VARCHAR(100),
//     files BLOB)";


// ----------------Alter tabel---------------

// $sql='ALTER TABLE registration ADD date_of_registration DATE NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER files;';

// ----------------inserting data into table-------------




// $sql = "SELECT * FROM `registration`;";

// $result = $conn->query($sql);



// if ($result->num_rows > 0) {


//     while ($data = $result->fetch_assoc()) {
//         echo $data['id'] . ' ' . $data['first_name'] . ' ' . $data['last_name'] . ' ' . $data['date_of_birth'] . ' ' . $data['email'] . ' ' . $data['pass'] . ' ' . $data['phone_number'] . ' ' . $data['qualification'] . '<br>';
//     }
// } else {
//     echo '0 fields';
// }


$conn->close();

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <?php include('./header.php'); ?>

</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;

    }

    .formoutter {

        width: 60%;
        display: block;
    }

    .bg-gra-02 {
        background: -webkit-gradient(linear, left bottom, right top, from(#fc2c77), to(#6c4079));
        background: -webkit-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: -moz-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: -o-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: linear-gradient(to top right, #fc2c77 0%, #6c4079 100%);
    }

    .textcolor {
        color: white;
    }
</style>

<body class="bg-gra-02">

    <div class="container mt-5 mx-auto justify-content-center shadow-lg d-flex rounded-5">
        <div class="container mt-5">
            <h3 class="text-center textcolor ">Register Yourself</h3>
            <form action="" method="post" class="row" onsubmit="return empty()" id="manage-member" autocomplete="off">
                <div class="col-4 mb-1">
                    <label for="fname" class="form-label textcolor">First Name : </label>
                    <input type="text" class="form-control" name="fname" id="fname" onkeyup="namecheck(this.id)">
                    <small class="text-white" id="fnameerror"><?php if (isset($firstNameError) && isset($lastNameError)) {
                                                                    echo $firstNameError;
                                                                }; ?></small>
                </div>
                <div class="col-4 mb-1">
                    <label for="mname" class="form-label textcolor">Middle Name : </label>
                    <input type="text" class="form-control" name="mname" id="mname" onkeyup="namecheck(this.id)">
                </div>
                <div class="col-4 mb-1">
                    <label for="lname" class="form-label textcolor">Last Name : </label>
                    <input type="text" class="form-control" name="lname" id="lname" onkeyup="namecheck(this.id)">
                </div>
                <div class="col-md-12 mb-1">
                    <label for="dob" class="form-label textcolor">Date of Birth :</label>
                    <input type="date" class="form-control" name="dob" id="dob" oninput="checkdate()" required>
                    <small class="text-white" id="doberr"><?php if (isset($dateErr)) {
                                                                echo $dateErr;
                                                            }; ?></small>
                </div>
                <div class="col-md-12  mb-1 ">
                    <label for="contact" class="form-label textcolor">Phone Number : </label>
                    <input type="number" class="form-control" id="contact" name="contact" onkeyup="phonecheck()" required>
                    <small class="text-white" id="numerr"><?php if (isset($numberErr)) {
                                                                echo $numberErr;
                                                            }; ?></small>
                </div>
                <div class="col-md-12 mb-1">
                    <p class="mb-1 textcolor">Gender:</p>
                    <input type="radio" id="male" name="gender" value="male" required>
                    <label for="male" class=" textcolor">MALE</label>
                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female" class=" textcolor">FEMALE</label>
                    <input type="radio" id="others" name="gender" value="others">
                    <label for="others" class=" textcolor">OTHERS</label>
                    <small class="text-white" id="gendererror"><?php if (isset($genderError)) {
                                                                    echo $genderError;
                                                                }; ?></small>
                </div>
                <div class="col-md-12 mb-1">
                    <label for="email" class="form-label textcolor">E - mail : </label>
                    <input type="email" class="form-control" id="email" name="mail" required>
                    <small class="text-white" id="emailErr"><?php if (isset($emailErr)) {
                                                                echo $emailErr;
                                                            }; ?></small>
                </div>
               

                <div class="col-md-12 mb-1">
                    <label for="address" class="textcolor">Address : </label>
                    <textarea name="address" id="address" class="form-control"></textarea>
                    <small class="text-white" id="addressErr"><?php if (isset($addressErr)) {
                                                                    echo $addressErr;
                                                                }; ?></small>
                </div>
                <button type="submit" id="submit" name="sbmt" class="btn btn-outline-danger shadow-lg textcolor  col-2 mx-auto my-3 mb-4 ">Submit</button>
            </form>
        </div>


    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js " integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe " crossorigin="anonymous "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/loginform.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script>
        function empty() {
            let a = $('small').text();
            if (a == '') {
                return true;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Values are Incorrect!'
                })
                window.href = "registration.php";
                $('small').text('');
                return false;
            }
        }


        $('#manage-member').submit(function(e) {
            e.preventDefault();
            var fname = $('#fname').val();
            var mname = $('#mname').val();
            var lname = $('#lname').val();
            var dob = $('#dob').val();
            var gender = $('#gender').val();
            var contact = $('#contact').val();
            var address = $('#address').val();
            var email = $('#email').val();
           

            // console.log(fname);

            $.ajax({
                type: 'POST',
                url: 'ajax.php?action=register_member',
                data: {
                    fname: fname,
                    mname: mname,
                    lname: lname,
                    dob: dob,
                    gender: gender,
                    contact: contact,
                    email: email,
                    address: address,
                    sbmt:""


                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("some error")
                },
                success: function(data, resp) {
                    console.log(data);
                    if (resp == 1) {

                        // alert_toast("Data successfully saved.",'success')
                        // setTimeout(function(){
                        // 	location.reload()
                        // },1000)
                    } else if (resp == 2) {
                        console.log("dasdgrdhzta");
                        $('#msg').html('<div class="alert alert-danger">ID No already existed.</div>')

                    }
                }
            })
        })
    </script>
     <!-- <script>
        // console.log($('#fname').val());

        $('#submit').click(function() {
            let a = $('small').text();
            if (a == '') {

                var firstName = $('#fname').val();
                var lastName = $('#lname').val();
                var dob = $('#dob').val();
                var phone = $('#phone_number').val();
                var mail = $('#email').val();
                var address = $('#address').val();
                var user_id = $('#user_id').val();

                $.ajax({
                    type: "POST",
                    url: 'modify.php',
                    data: {
                        fname: firstName,
                        lname: lastName,
                        dob: dob,
                        phone_number: phone,
                        mail: mail,
                        address: address,
                        id: user_id,
                        sbmt: ""
                    },
                    success: function(data, status) {
                        var stat = data.slice(0, 2);
                        if (stat == "aw") {

                            Toast.fire({
                                icon: 'success',
                                title: 'Record Change successfully.... '
                            })
                        }
                    }
                });
            } else {
                alert("values are incorrect");
                
            }
            // alert('hello');
        });
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script> -->
   
</body>

</html>








































<!-- 

<div class="container-fluid">
	<form action="" id="manage-member">
		<div id="msg"></div>
				<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>" class="form-control">
		<div class="row form-group">
			<div class="col-md-4">
						<label class="control-label">ID No.</label>
						<input type="text" name="member_id" class="form-control" value="<?php echo isset($member_id) ? $member_id : '' ?>" >
						<small><i>Leave this blank if you want to a auto generate ID no.</i></small>
					</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename : '' ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Contact #</label>
				<input type="text" name="contact" class="form-control" value="<?php echo isset($contact) ? $contact : '' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Gender</label>
				<select name="gender" required="" class="custom-select" id="">
					<option <?php echo isset($gender) && $gender == 'Male' ? 'selected' : ''
                            ?>>Male</option>
					<option <?php echo isset($gender) && $gender == 'Female' ? 'selected' : ''
                            ?>>Female</option>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
				<label class="control-label">Address</label>
				<textarea name="address" class="form-control"><?php echo isset($address) ? $address : '' ?></textarea>
			</div>
		</div>
		
	</form>
</div>
 -->