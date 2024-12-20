<?php
include 'config.php';

$message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM user WHERE id = '$id'";
    $execut = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($execut);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $dob_day = $_POST['dob_day'];
    $dob_month = $_POST['dob_month'];
    $dob_year = $_POST['dob_year'];
    $gender = $_POST['gender'] ?? '';
    $hobbies = isset($_POST['hobbies']) && is_array($_POST['hobbies']) ? $_POST['hobbies'] : [];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $Profile = $_FILES['profile']['name'];  
    $Profiletemp = $_FILES['profile']['tmp_name'];

    // $dob = $dob_year . '-' . $dob_month . '-' . $dob_day;
  
    $errors = [];

    // validation 
    // 1)first name 
    if (empty($fname) || !preg_match("/^[a-zA-Z\s]+$/", $fname)) {
        $errors['fname'] = "First Name is required and should only contain letters and spaces.";
    }

    //2) last name 
    if (empty($lname) || !preg_match("/^[a-zA-Z\s]+$/", $lname)) {
        $errors['lname'] = "Last Name is required and should only contain letters and spaces.";
    }

    // 3)email address

    if(empty($email) || !filter_var($email ,FILTER_VALIDATE_EMAIL))
    {
        $errors['email'] = 'Please enter a valid email.';
    }else {
        $emailQuery = isset($id) ? "SELECT email FROM user WHERE email = '$email' AND id != '$id'" : "SELECT email FROM user WHERE email = '$email'";
        $emailResult = mysqli_query($conn, $emailQuery);
        if (mysqli_num_rows($emailResult) > 0) {
            $errors['email'] = "Email is already registered. Please use a different email.";
        }
    }

    // 4) Date of Birth

    if(empty($dob_day) || empty($dob_month) || empty($dob_year))
    {
        $errors['dob']  = "Please select a valid date of birth.";   
    }


    // 5) gender 
    if(empty($gender))
    {
        $errors['gender']= 'Please select your gender';
    }

    // 6) hobbies
    if(count($hobbies)<2)
    {
        $errors['hobbies'] = 'Please select at least two hobbies.';
    }

    // 7) address
    if(empty($address))
    {
        $errors['address'] = 'Address is required.';
    }

    // 8) city
    if (empty($city)) {
        $errors['city'] = "Please select a city.";
    }

    // 9) state
    if (empty($state)) {
        $errors['state'] = "Please select a state.";
    }

    // 10) zipcode
     if (empty($zipcode) || !preg_match("/^\d{6}$/", $zipcode)) {
        $errors['zipcode'] = "Please enter a valid 6-digit zipcode.";
    }

    //11) profile pic
   if (empty($Profile) && !isset($data['profilepic'])) {
        $errors['profile'] = "Profile picture is required.";
    } elseif ($Profile && !in_array(pathinfo($Profile, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
        $errors['profile'] = "Profile photo must be in JPG, JPEG, or PNG format.";
    }

    if (!empty($errors)) {
        $message = "<ul>";
        foreach ($errors as $key => $error) {
            $message .= "<li style='color: red;'>$error</li>";
        }
        $message .= "</ul>";

    }else{
     $dob = "$dob_year-$dob_month-$dob_day";
        if ($Profile) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileExtension = pathinfo($Profile, PATHINFO_EXTENSION);
            $uniqueFilename = time() . '.' . $fileExtension;
            $uploadPath = './uploads/' . $uniqueFilename;
            if (!move_uploaded_file($Profiletemp, $uploadPath)) {
                $errors['profile'] = "Failed to upload the profile picture.";
            }
        } else {
            $uniqueFilename = isset($data['profilepic']) ? $data['profilepic'] : '';
        }

         if (empty($errors)) {
            if (isset($id)) {
                $query = "UPDATE `user` SET `fName`='$fname', `lName`='$lname', `email`='$email', `dob`='$dob', `gender`='$gender', `hobbies`='" . implode(",", $hobbies) . "', `address`='$address', `city`='$city', `state`='$state', `zipcode`='$zipcode', `profilepic`='$uniqueFilename' WHERE `id`='$id'";
                $message = "Record updated successfully!";
            } else {
                $query = "INSERT INTO `user`(`fName`, `lName`, `email`, `dob`, `gender`, `hobbies`, `address`, `city`, `state`, `zipcode`, `profilepic`) 
                          VALUES ('$fname', '$lname', '$email', '$dob', '$gender', '" . implode(",", $hobbies) . "', '$address', '$city', '$state', '$zipcode', '$uniqueFilename')";
                $message = "User registered successfully!";
            }

            $execut = mysqli_query($conn, $query);
            if ($execut) {
                header("Location: manageusers1.php?message=" . urlencode($message));
                exit();
            } else {
                $message = "Error in processing your request.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background-color: #f0f0f0;
        }

        .form-container {
            width: 50%;
            max-width: 600px;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-controler {
            padding: 8px;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-controler label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-controler input,
        .form-controler textarea,
        .form-controler select {
            width: 95%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .form-controler-dob {
            padding: 8px;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }

        .form-controler-dob select {

            width: 32%; 
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-controler-dob label {
             display: flex;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-controler input[type="radio"],
        .form-controler input[type="checkbox"] {
            
            width: auto;
        }

        .gender-options, .hobby-options {
            padding: 10px;
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .form-controler button {
            width: 100%;
            padding: 15px;
            background-color: #1c74b8;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .form-controler button:hover {
            background-color: #4598a0;
            transform: translateY(-3px);
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .form-controler-gh {
            padding: 8px;
            margin-top: 15px;
        }

        .form-container input[type="file"] {
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2><?php echo isset($id) ? 'Edit Profile' : 'Registration'; ?></h2>
        <!-- onsubmit="return validateForm()" --> 
        <form class="container" action="index.php<?php echo isset($data['id']) ? '?id=' . $data['id'] : ''; ?>" method="post" enctype="multipart/form-data" >

                 <div class="form-controler">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" placeholder="Enter Your First Name" value="<?php echo isset($data['fname']) ? $data['fname'] : (isset($fname) ? $fname : ''); ?>">
                <span class="error" id="first_name_error"> </span>
                <?php if (isset($errors['fname'])) { echo "<span class='error'>{$errors['fname']}</span>"; } ?>
            </div>

            <div class="form-controler">
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" placeholder="Enter Your Last Name" value="<?php echo isset($data['lname']) ? $data['lname'] : (isset($lname) ? $lname : ''); ?>">
                <span class="error" id="last_name_error"></span>
                 <?php if (isset($errors['lname'])) { echo "<span class='error'>{$errors['lname']}</span>"; } ?>
            </div>

            <div class="form-controler">
                <label for="email">Email Address:</label>
                <input type="text" name="email" id="email" placeholder="Enter Your Email Address" value="<?php echo isset($data['email']) ? $data['email'] : (isset($email) ? $email : ''); ?>">
                <span class="error" id="email_error"></span>
                 <?php if (isset($errors['email'])) { echo "<span class='error'>{$errors['email']}</span>"; } ?>

            </div>

            <div class="form-controler-dob">
                <label for="dob">Date Of Birth:</label>
                <select name="dob_day" id="dob_day">
                    <option value="">Day</option>
                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php echo isset($data['dob']) && date('d', strtotime($data['dob'])) == $i ? 'selected' : (isset($dob_day) && $dob_day == $i ? 'selected' : ''); ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>

                <select name="dob_month" id="dob_month">
                    <option value="">Month</option>
                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php echo isset($data['dob']) && date('m', strtotime($data['dob'])) == $i ? 'selected' : (isset($dob_month) && $dob_month == $i ? 'selected' : ''); ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>

                <select name="dob_year" id="dob_year">
                    <option value="">Year</option>
                    <?php for ($i = 1970; $i <= 2020; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php echo isset($data['dob']) && date('Y', strtotime($data['dob'])) == $i ? 'selected' : (isset($dob_year) && $dob_year == $i ? 'selected' : ''); ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <span class="error" id="dob_error"></span>
                 <?php if (isset($errors['dob'])) { echo "<span class='error'>{$errors['dob']}</span>"; } ?>
                
            </div>

           <div class="form-controler-gh">
                <label for="gender">Gender:</label><br>
                <div class="gender-options">
                    <input type="radio" id="male" name="gender" value="1" 
                        <?php echo isset($data['gender']) && $data['gender'] == '1' ? 'checked' : (isset($gender) && $gender == '1' ? 'checked' : ''); ?>>
                    <label for="male">Male</label>

                    <input type="radio" id="female" name="gender" value="2" 
                        <?php echo isset($data['gender']) && $data['gender'] == '2' ? 'checked' : (isset($gender) && $gender == '2' ? 'checked' : ''); ?>>
                    <label for="female">Female</label>
                </div>
                <span class="error"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></span>
            </div>

            <?php 
            $hobbies = [];
            if (isset($data['hobbies']) ) {
                $hobbies = explode(',', $data['hobbies']);  
            } elseif (isset($_POST['hobbies']) && is_array($_POST['hobbies'])) {
                $hobbies = $_POST['hobbies'];
            }
            ?>
           <div class="form-controler-gh">
            <label for="hobbies">Hobbies:</label><br>
            <div class="hobby-options">
                <label><input type="checkbox" name="hobbies[]" value="Reading" <?php echo in_array('Reading', $hobbies) ? 'checked' : ''; ?>> Reading</label>
                <label><input type="checkbox" name="hobbies[]" value="Traveling" <?php echo in_array('Traveling', $hobbies) ? 'checked' : ''; ?>> Traveling</label>
                <label><input type="checkbox" name="hobbies[]" value="Cooking" <?php echo in_array('Cooking', $hobbies) ? 'checked' : ''; ?>> Cooking</label>
                <label><input type="checkbox" name="hobbies[]" value="Sports" <?php echo in_array('Sports', $hobbies) ? 'checked' : ''; ?>> Sports</label>
            </div>
            <span class="error"><?php echo isset($errors['hobbies']) ? $errors['hobbies'] : ''; ?></span>
        </div>
    
            <div class="form-controler">
                <label for="address">Address:</label>
                <textarea name="address" id="address" placeholder="Enter Your full Address"><?php echo isset($data['address']) ? $data['address'] : (isset($address) ? $address : ''); ?></textarea>
                <span class="error" id="address_error"></span>
                <?php if (isset($errors['address'])) { echo "<span class='error'>{$errors['address']}</span>"; } ?>

            </div>

            <div class="form-controler">
                <label for="city">City:</label>
                <select name="city" id="city">
                    <option value="">Select City</option>
                    <?php
                    $cities = ['Rajkot', 'Jamnagar', 'Surat', 'Gandhinagar'];
                    $selected_city = isset($data['city']) ? $data['city'] : (isset($_POST['city']) ? $_POST['city'] : '');
                    foreach ($cities as $city) {
                        $selected = ($selected_city == $city) ? 'selected' : '';
                        echo "<option value=\"$city\" $selected>$city</option>";
                    }
                    ?>
                </select><br>
                <span class="error" id="city_error"><?php echo isset($city_error) ? $city_error : ''; ?></span>
                 <?php if (isset($errors['city'])) { echo "<span class='error'>{$errors['city']}</span>"; } ?>

            </div>

            <div class="form-controler">
                <label for="state">State:</label>
                <select name="state" id="state">
                    <option value="">Select State</option>
                    <?php
                    $states = ['Gujarat', 'Mumbai', 'UP'];
                    $selected_state = isset($data['state']) ? $data['state'] : (isset($_POST['state']) ? $_POST['state'] : '');
                    foreach ($states as $state) {
                        $selected = ($selected_state == $state) ? 'selected' : '';
                        echo "<option value=\"$state\" $selected>$state</option>";
                    }
                    ?>
                </select><br>
                <span class="error" id="state_error"><?php echo isset($state_error) ? $state_error : ''; ?></span>
                 <?php if (isset($errors['state'])) { echo "<span class='error'>{$errors['state']}</span>"; } ?>

            </div>

            <div class="form-controler">
                <label for="zipcode">Zipcode:</label>
                <input type="text" name="zipcode" id="zipcode" placeholder="Enter Your Zipcode" value="<?php echo isset($data['zipcode']) ? $data['zipcode'] : (isset($zipcode) ? $zipcode : ''); ?>">
                <span class="error" id="zipcode_error"></span>
                 <?php if (isset($errors['zipcode'])) { echo "<span class='error'>{$errors['zipcode']}</span>"; } ?>

            </div>
        
           <div class="form-controler">
                <?php if (isset($data['profilepic']) && $data['profilepic']): ?>
                    <img src="./uploads/<?php echo $data['profilepic']; ?>" alt="Profile Picture" width="100" height="100"><br>
                <?php endif; ?>
                <label for="profile">Profile Picture:</label>
                <input type="file" name="profile" id="profile">
                <span class="error" id="profile_error"></span>
                <?php if (isset($errors['profile'])) { echo "<span class='error'>{$errors['profile']}</span>"; } ?>
            </div>

            <div class="form-controler">
                <button type="submit"><?php echo isset($id) ? 'Update Record' : 'Save Record'; ?></button>
            </div>
           
        </form>
    </div>

    <script>
        function validateForm() {
            let hasErrors = false;
            document.querySelectorAll('.error').forEach(error => error.textContent = '');
            document.querySelectorAll('input, select, textarea').forEach(input => input.style.borderColor = '#ccc');

            const fname = document.getElementById('fname').value;
            const lname = document.getElementById('lname').value;
            const email = document.getElementById('email').value;
            const dob_day = document.getElementById('dob_day').value;
            const dob_month = document.getElementById('dob_month').value;
            const dob_year = document.getElementById('dob_year').value;
            const gender = document.querySelector('input[name="gender"]:checked');
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const zipcode = document.getElementById('zipcode').value;
            const profile = document.getElementById('profile').value;
            const hobbies = document.querySelectorAll('input[name="hobbies[]"]:checked');
            const nameRegex = /^[a-zA-Z\s]+$/;

            if (!fname || !nameRegex.test(fname)) {
                document.getElementById("first_name_error").textContent = "First Name is required and should not contain special characters.";
                document.getElementById("fname").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("fname").focus();
                hasErrors = true;
            }

            if (!lname || !nameRegex.test(lname)) {
                document.getElementById("last_name_error").textContent = "Last Name is required and should not contain special characters.";
                document.getElementById("lname").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("lname").focus();
                hasErrors = true;
            }

            if (!email || !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(email)) {
                document.getElementById("email_error").textContent = "Please enter a valid email.";
                document.getElementById("email").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("email").focus();
                hasErrors = true;
            }

            if (!dob_day || !dob_month || !dob_year) {
                document.getElementById("dob_error").textContent = "Please select a valid date of birth.";
                document.getElementById("dob_day").style.borderColor = 'red';
                document.getElementById("dob_month").style.borderColor = 'red';
                document.getElementById("dob_year").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("dob_day").focus();
                hasErrors = true;
            }

            if (!gender) {
                document.getElementById("gender_error").textContent = "Please select your gender.";
                hasErrors = true;
            }

            if (!address) {
                document.getElementById("address_error").textContent = "Please enter your address.";
                document.getElementById("address").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("address").focus();
                hasErrors = true;
            }

            if (!city) {
                document.getElementById("city_error").textContent = "Please select a city.";
                document.getElementById("city").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("city").focus();
                hasErrors = true;
            }

            if (!state) {
                document.getElementById("state_error").textContent = "Please select a state.";
                document.getElementById("state").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("state").focus();
                hasErrors = true;
            }

            if (!zipcode || !/^\d{6}$/.test(zipcode)) {
                document.getElementById("zipcode_error").textContent = "Please enter a valid 6-digit zipcode.";
                document.getElementById("zipcode").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("zipcode").focus();
                hasErrors = true;
            }

            if (!profile && !<?php echo isset($data['profilepic']) ? 'true' : 'false'; ?>) {
                document.getElementById("profile_error").textContent = "Profile picture is required.";
                document.getElementById("profile").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("profile").focus();
                hasErrors = true;
            } else if (profile && !/\.(jpg|jpeg|png)$/i.test(profile)) {
                document.getElementById("profile_error").textContent = "Profile photo must be in jpg/jpeg/png format.";
                document.getElementById("profile").style.borderColor = 'red';
                if (!hasErrors) document.getElementById("profile").focus();
                hasErrors = true;
            }

            if (!hobbies.length < 2) {
                document.getElementById("hobbies_error").textContent = "Please select at least two hobbies.";
                hasErrors = true;
            }

            return !hasErrors;
        }
    </script>
</body>
</html>