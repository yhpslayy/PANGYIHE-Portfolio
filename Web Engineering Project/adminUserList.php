<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Registered User List</title>
</head>
<?php 
            
    $id = $_SESSION['id'];
    $query = mysqli_query($con,"SELECT*FROM administrator WHERE AdminID='$id'");

    while($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['AdminName'];
        $res_id = $result['AdminID'];
		$res_pic = $result['AdminProfile'];
    }
             
?>
<body>
	<table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:30%; font-size: 50px; text-align: right"><a href="adminHome.php">FKOM KIOSK</a></th>
			<th class="tht" style="text-align: left; padding-left: 40px"><img src="UMPSA logo.png" alt="UMPSA Logo" width="101" height="75"></th>
			<th class="tht" style="width:10%; text-align: right; padding-right: 10px">
			<?php
				if ($res_pic != NULL) {
					echo "<img src='" . $res_pic . "' alt='Profile Picture' height='75'>";
				} else {
					echo "<img src='account.png' alt='Default Profile Picture' height='75'>";
				}
			?></th>
			<th class="tht" style="width:10%; text-align: left; font-size: 25px"><a><?php echo "<a href='edit.php?Id=$res_id'>$res_Uname</a>"; ?></a></th>
			<th class="tht" style="width:16%; text-align: left; padding: 0px"><a href="php/logout.php"> <button class="btn">Log Out</button> </a></th>
		</tr>
	</table>

    <main>

       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p style="font-size: 30px; padding:5px"><b>Administrator Dashboard</b></p>
            </div>
          </div>
		  <div class="bottom">
            <div class="box">
                <ul>
					<li><a href="adminHome.php">Food Vendor Approval</a></li>
					<li><a href="adminVendorList.php">Food Vendor List</a></li>
					<li><a class="active" href="#">Registered User List</a></li>
					<li><a href="adminAddUser.php">Create New User</a></li>
					<li><a href="adminUserChart.php">Insights Report</a></li>
				</ul>
				<br>
			<?php
			$link = mysqli_connect("localhost","root","","kiosksys") or die("Couldn't connect");
			$query = "SELECT * FROM registereduser" or die(mysqli_connect_error());
	
			$result = mysqli_query($link, $query);
			
			if (isset($_POST['edit'])) {
			$uID = $_POST["userId"];

			$query = "SELECT * FROM registereduser WHERE UserID = '$uID'";
			$result = mysqli_query($link, $query);

			if ($result) {
				$row = mysqli_fetch_assoc($result);

				$editID = $row["UserID"];
				$editName = $row["UserName"];
				$editEmail = $row["UserEmail"];
				$editPassword = $row["UserPassword"];
				$editQR = $row["UserQRCode"];
        ?>
		<h2 style="padding:10px">Edit <?php echo $editName; ?> Profile</h2></header><hr>
            <form method="post">
				<input type="hidden" name="editID" value="<?php echo $editID; ?>" readonly>
                <div style="padding:10px">
                    <label for="editName"><b>Username: </b></label>
                    <input type="text" name="editName" style="padding:5px; margin-left:55px; width:400px" value="<?php echo $editName; ?>" required>
                </div>
				
				<div style="padding:10px">
					<label for="editEmail"><b>Email: </b></label>
					<input type="text" name="editEmail" style="padding:5px; margin-left:90px; width:400px" value="<?php echo $editEmail; ?>" required>
				</div>
				
				<div style="padding:10px">
                    <label for="editPassword"><b>Password: </b></label>
                    <input type="text" name="editPassword" style="padding:5px; margin-left:57px; width:400px" value="<?php echo $editPassword; ?>" required>
                </div>

                <div style="padding:10px"><hr>
                    <input type="submit" class="btn" name="update" value="Update">
                </div>
                
            </form>
        <?php
			exit();
			}
		}

		if (isset($_POST['update'])) {
			$editID = $_POST['editID'];
			$editName = $_POST['editName'];
			$editEmail = $_POST['editEmail'];
			$editPassword = $_POST['editPassword'];
			$editQR = '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $editName . '&size=80x80">';

			$updateQuery = "UPDATE registereduser SET UserName='$editName', UserEmail='$editEmail', UserPassword='$editPassword', UserQRCode='$editQR' WHERE UserID='$editID'";
			$updateResult = mysqli_query($link, $updateQuery);

			if ($updateResult) {
				header("Location: adminUserList.php");
				echo '<script>alert("User information updated successfully.");</script>';
				
			} else {
				echo "Error updating User information.";
			}
		}
		if (isset($_POST['delete'])) {
				$uID = $_POST["userId"];

				mysqli_query($link, "DELETE FROM registereduser WHERE UserID='$uID'") or die("Error occurred");
				
				header("Location: adminUserList.php");
				exit();
			}
		?>
		<table class="botTable">
				<tr style="background-color:#d8f3ea; border: 2px solid black;">
					<td class="tdb" width="8%"><b>User ID</b></td>
					<td class="tdb" width="25%"><b>User Name</b></td>
					<td class="tdb" width="35%"><b>User Email</b></td>
					<td class="tdb" width="15%"><b>User QR Code</b></td>
					<td class="tdb"><b>Action</b></td>
				</tr>
		</table>
		<?php
			if (mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					$uID = $row["UserID"];
					$uName = $row["UserName"];
					$uEmail = $row["UserEmail"];
					$uQR = $row["UserQRCode"];
			?>	
		<form method="post">
			<table class="botTable" width="100%">
				<tr>
					<td class="tdb" width="8%"><?php echo $uID; ?></td>
					<td class="tdb" width="25%"><?php echo $uName; ?></td>
					<td class="tdb" width="35%"><?php echo $uEmail; ?></td>
					<td class="tdb" width="15%"><?php echo $uQR ?></td>	
					<td class="tdb">
                        <input type="hidden" name="userId" value="<?php echo $uID; ?>">
                        <input type="submit" class="btn" name="edit" value="Edit" required>
                        <input type="submit" class="btn" name="delete" value="Delete" required>
                    </td>
				</tr>
			</table>
		</form>
			<?php
				}
			} else {
				echo "0 user";

			}
			?>
            </div>
          </div>
       </div>

    </main>
</body>
</html>