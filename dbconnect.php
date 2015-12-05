<?php 

$dsn = 'mysql:dbname=project;host=54.183.146.117'; 
$user = 'DBPro'; 
$password = '*********'; 

try {
	$db = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	exit(0); 
}

start: 

while(1){
	$query = ''; 
	$command = './a.out '; 

	echo "\nPlease input your sql command:\n\n"; 

	$line = trim(fgets(STDIN)); 

	if(strpos($line, 'INSERT') !==false){

		$pieces = explode(" ", $line);
		if(!$pieces[1] || !$pieces[2]){
			echo "\n>>>Error: Something wrong with your query. Please double check syntax.<<<\n"; 
			goto start; 
		}
		$query = "INSERT INTO Employees VALUES(".$pieces[1].", ".$pieces[2].","; 

			$command = $command.$pieces[3]." encode"; 
			//run './a.out 1234 encode'. 
			echo "\n------------------------Client Encoding:--------------------\n"; 
			$last_line = system($command, $retval);
			$query = "INSERT INTO Employees VALUES(".$pieces[1].", ".$pieces[2].",'".$last_line."');"; 
			echo "------------------------Encoding Done-------------------------\n";  

			$result = $db->query($query); 

			if (!$result) {
				echo "\nInsert Command failed. Please check your Employee's ID.\n"; 
			}

		} elseif (strpos($line, 'SELECT *') !==false){
			$line=str_replace("*", "* FROM Employees", $line);

			$result = $db->query($line); 
			//if result null, catch error. 
			if(!$result){
				echo "\n>>>Error: Something wrong with your query. Please double check syntax.<<<\n"; 
				goto start; 
			}
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 

			if(count($value) == 0){

				echo "\nError: No Employee in the table\n"; 

			} else {

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
			}

		} elseif (preg_match("/SELECT (\d)+/", $line)){
			$pieces = explode(" ", $line);
			$query = 'SELECT * FROM Employees WHERE id = '.$pieces[1]; 

			$result = $db->query($query); 
			//if result null, catch error. 
			if(!$result){
				echo "\n>>>Error: Something wrong with your query. Please double check syntax.<<<\n"; 
				goto start; 
			}
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 

			if(count($value) == 0){

				echo "\nError: No such employee under this ID \n"; 

			} else {

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

			}

		} elseif(strpos($line, 'SUM') !==false && strpos($line, 'GROUP')==false && strpos($line, 'group')==false){
			$line=str_replace("SUM", "SUM_HE(salary) FROM Employees", $line);
			//print_r($line); 

			$result = $db->query($line); 
			//if result null, catch error. 
			if(!$result){
				echo "\n>>>Error: Something wrong with your query. Please double check syntax.<<<\n"; 
				goto start; 
			}
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 

			if(count($value) == 0 || $value[0]['SUM_HE(salary)'] == 'db5ea54bbf75605db50abb091a8a18219e7935b720314ce5b92d9bb1550963e'){

				echo "\nError: no record/Employee in the table \nsalary\nNULL\n"; 

			} else {

			echo "\n------------------------Client Decoding:--------------------\n";
			foreach($value as $key => $val){
				$command = './a.out '; 
				$command = $command.$val['SUM_HE(salary)']." decode";
				echo $command."\n"; 
			//run './a.out 0a3b9b9c decode'. 
				$last_line = system($command, $retval);
				$value[$key]['SUM_HE(salary)'] = $last_line;
			}
			echo "------------------------Decoding Done-------------------------\n"; 

			echo "salary\n"; 
			foreach($value as $val){
				echo $val['SUM_HE(salary)']."\n"; 
			}
		}
		} elseif(strpos($line, 'SUM') !==false){
			$line=str_replace("SUM", "age, SUM_HE(salary) FROM Employees", $line);
			print_r($line); 

			$result = $db->query($line); 
			//if result null, catch error. 
			if(!$result){
				echo "\n>>>Error: Something wrong with your query. Please double check syntax.<<<\n"; 
				goto start; 
			}
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 

			if(count($value) == 0){

				echo "\nError: No employee/record in the table \nage   salary\nNULL  NULL\n"; 

			} else {

			echo "\n------------------------Client Decoding:--------------------\n";
			foreach($value as $key => $val){
				$command = './a.out '; 
				if ($val['SUM_HE(salary)'] == 0){
					$value[$key]['SUM_HE(salary)'] = "NULL"; 
					continue; 
				}
				$command = $command.$val['SUM_HE(salary)']." decode";
				echo $command."\n"; 
			//run './a.out 0a3b9b9c decode'. 
				$last_line = system($command, $retval);
				$value[$key]['SUM_HE(salary)'] = $last_line;
			}
			echo "------------------------Decoding Done-------------------------\n"; 

			echo "age   salary\n"; 
			foreach($value as $val){
				echo $val['age']."      ".$val['SUM_HE(salary)']."\n"; 
			}
		}

		} elseif(strpos($line, 'AVG') !==false){
			$line1=str_replace("AVG", "age, SUM_HE(salary), count(*) FROM Employees", $line);
		//print_r($line1); 

			$result = $db->query($line1); 
			//if result null, catch error. 
			if(!$result){
				echo "\n>>>Error: Something wrong with your query. Please double check syntax. (AVG is a aggregate function!)<<<\n"; 
				goto start; 
			}
			$value = $result->fetchAll(PDO::FETCH_ASSOC); 
		

			echo "\n------------------------Client Decoding:--------------------\n";
			foreach($value as $key => $val){
				$command = './a.out '; 
				$command = $command.$val['SUM_HE(salary)']." decode";
			//run './a.out 0a3b9b9c decode'. 
				$last_line = system($command, $retval);
				$value[$key]['SUM_HE(salary)'] = $last_line;
			}
			echo "------------------------Decoding Done-------------------------\n";

			$tmp_array = array(); 
			foreach($value as $val){
				$tmp_array[$val['age']] = array("AVG"=>($val['SUM_HE(salary)']/$val['count(*)']), "age"=>$val[age]);
			}

			if(empty($tmp_array)){
				$tmp_array['NULL']['AVG']="NULL"; 
			}

			echo "age   AVG(salary)\n"; 
			foreach($tmp_array as $key=>$val){
				echo $key."      ".$val['AVG']."\n"; 
			}

		} elseif(strpos($line, 'exit') !==false || strpos($line, 'EXIT') !==false){
			$pdo = null;
			echo "Disconnected from sql. \n";
			exit(0); 
		} else {
			echo "Warning: Query cannot be recognized.\nPlease re-try again. (All queries are case-seneitive)\n"; 
		}



	}





	?> 
