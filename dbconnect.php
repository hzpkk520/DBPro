<?php 

$dsn = 'mysql:dbname=project;host=54.183.146.117'; 
$user = 'DBPro'; 
$password = '*************'; 

try {
	$db = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	exit(0); 
}


while(1){
	$query = ''; 
	$command = './a.out '; 

	echo "\nPlease input your sql command:\n\n"; 

	$line = trim(fgets(STDIN)); 

	if(strpos($line, 'INSERT') !==false){

		$pieces = explode(" ", $line);
		$query = "INSERT INTO Employees VALUES(".$pieces[1].", ".$pieces[2].","; 

		$command = $command.$pieces[3]." encode"; 
		//run './a.out 1234 encode'. 
		echo "\n------------------------Client Encoding:--------------------\n"; 
		$last_line = system($command, $retval);
		$query = "INSERT INTO Employees VALUES(".$pieces[1].", ".$pieces[2].",'".$last_line."');"; 
		echo "------------------------Encoding Done-------------------------\n";  

		$result = $db->query($query); 

		if($result){
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 
		}


	} elseif (strpos($line, 'SELECT *') !==false){
		$line=str_replace("*", "* FROM Employees", $line);

		$result = $db->query($line); 
		$value = $result->fetchAll(PDO::FETCH_ASSOC); 

		echo "\n------------------------Client Decoding:--------------------\n";
		foreach($value as $key => $val){
			$command = './a.out '; 
			$command = $command.$val['salary']." decode";
			//run './a.out 0a3b9b9c decode'. 
			$last_line = system($command, $retval);
			$value[$key]['salary'] = $last_line;
		}
		echo "------------------------Decoding Done-------------------------\n"; 

		//display table content 
		echo "id,   age,   salary\n"; 
		foreach($value as $val){
			echo $val['id']."    ".$val['age']."      ".$val['salary']."\n"; 
		}

	} elseif (preg_match("/SELECT (\d)+/", $line)){
		$pieces = explode(" ", $line);
		$query = 'SELECT * FROM Employees WHERE id = '.$pieces[1]; 

		$result = $db->query($query); 
		$value = $result->fetchAll(PDO::FETCH_ASSOC); 

		echo "\n------------------------Client Decoding:--------------------\n";
		foreach($value as $key => $val){
			$command = './a.out '; 
			$command = $command.$val['salary']." decode";
			//run './a.out 0a3b9b9c decode'. 
			$last_line = system($command, $retval);
			$value[$key]['salary'] = $last_line;
		}
		echo "------------------------Decoding Done-------------------------\n"; 

		//display table content 
		echo "id,   age,   salary\n"; 
		foreach($value as $val){
			echo $val['id']."    ".$val['age']."      ".$val['salary']."\n"; 
		}

	} elseif(strpos($line, 'SUM') !==false){
		$line=str_replace("SUM", "SUM(age) FROM Employees", $line);
		print_r($line); 

		$result = $db->query($line); 
		$value = $result->fetchAll(PDO::FETCH_ASSOC); 


		foreach($value as $key => $val){
			$command = './a.out '; 
			$command = $command.$val['SUM(age)']." decode";
			//run './a.out 0a3b9b9c decode'. 
			//$last_line = system($command, $retval);
			$value[$key]['SUM(age)'] = $last_line;
		}

	} elseif(strpos($line, 'AVG') !==false){
		$line1=str_replace("AVG", "age, SUM(age), count(*) FROM Employees", $line);
		print_r($line1); 

		$result = $db->query($line1); 
		$value = $result->fetchAll(PDO::FETCH_ASSOC); 

		//run './a.out 0a3b9b9c decode'. 
		//$last_line = system($command, $retval);

		foreach($value as $key => $val){
			$command = './a.out '; 
			$command = $command.$val['SUM(age)']." decode";
			//run './a.out 0a3b9b9c decode'. 
			//$last_line = system($command, $retval);
			//$value[$key]['SUM(age)'] = $last_line;
		}

		$tmp_array = array(); 
		foreach($value as $key => $val){
			$tmp_array[$key] = array("AVG"=>($val['SUM(age)']/$val['count(*)']), "age"=>$val[age]); 
		}

		print_r($tmp_array);
	} elseif(strpos($line, 'EXIT') !==false){
		$pdo = null;
		echo "Disconnected from sql. \n";
		exit(0); 
	} else {
		echo "Warning: Query cannot be recognized.\nPlease re-try again. (All queries are case-seneitive)\n"; 
	}



}





?> 
