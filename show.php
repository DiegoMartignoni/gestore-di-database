<!DOCTYPE html>
<html>
<head>
    <title>Belingheri DB</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include ("./credenziali.php");
$conn1 = new mysqli($servername, $username, $password);
$sql = "SHOW DATABASES";
$result = $conn1->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
	echo "<h2>database: $dbname</h2>";
	echo "<label for='selDB'>Seleziona il DB:</label><select class='form-control' onchange='cambiaDB(this.value)' id='selDB'><option selected disabled value=''>Seleziona un DB</option>";
    while($row = $result->fetch_assoc()) {
        foreach ($row as $value) {
                echo "<option value='".$value."'>".$value."</option>";
        }
    }
	echo "</select>";
} else {
    echo "0 results";
}
$conn1->close();
	
	
?>
<script>
	function cambiaDB(t){
	console.log(t);
	if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
                location.reload();
            }
        };
		var param="dbname="+ t;
        xmlhttp.open("POST","./credenziali.php",true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send(param);
    }
</script>
<?php
include ("./credenziali.php");
try{
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SHOW TABLES";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$i=0;
	   $tablename=$row['Tables_in_'.$dbname];
	   echo "<h2>Nome: <b>$tablename</b></h2>
			<table class='table table-striped'>";
	   $richiedistruttura="SHOW COLUMNS FROM $tablename";
	   $struttura = $conn->query($richiedistruttura);
	   while($row = $struttura->fetch_assoc()) {
			if($i==0){
			echo "<tr>";
			foreach ($row as $key=>$value) {
					echo "<th scope='col'>".$key."</th>";
			}
			echo "</tr>";
			$i++;
			}
			echo "<tr>";
			foreach ($row as $value) {
					echo "<td>".$value."</td>";
			}
			echo "</tr>";
		}
		$datisql="SELECT * FROM $tablename";
		$dati = $conn->query($datisql);
		echo "<tr><th  scope='col'> Dati:</th></tr><tr>";
		$i=0;
		while($row = $dati->fetch_assoc()) {
			if($i==0){
				echo "<tr>";
				foreach ($row as $key=>$value) {
					echo "<th scope='col' >".$key."</th>";
				}
				echo "</tr>";
				$i++;
			}
			echo "<tr>";
				foreach ($row as  $value) {
					echo "<td>".$value."</td>";
				}
			echo "</tr>";
		}
		echo "</table><hr>";
	}
	$conn->close();
	}catch(Exception $e)
			{
			echo 'Seleziona un database';
			}
?>
