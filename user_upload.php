<?php
	//variable $isRunInsertDB: to judge whether insert users into table
	//variable $usersArray: the users array read from users.csv file
	//variable $argumentsArray: all the input arguments from command line
	$isRunInsertDB = true;
	$usersArray = array();
	$argumentsArray = array();


	//call command line parse function to get all the input arguments
	$argumentsArray = commandArgumentsParse($argc,$argv);

	//if the input arguments contain the "--help" argument, call the help
	//function to show list of directives with details
	if ($argumentsArray["help"]=="help"){
		exitWithHelpInfo();
	}

	//if the input arguments contain the "--create_table" argument, call the 
	//function to create or recreate users table and no future action to be executed
	if ($argumentsArray["create_table"]=="create_table"){
		if ($argumentsArray["h"]==""||$argumentsArray["u"]==""){
			echo "please input the [-u] and [-h] arguments\n";
			exitWithHelpInfo();
		}else {
			usersTableCreate($argumentsArray);
			exit(1);
		}
		
	}

	//if the input arguments contain the "--dry_run" argument, call all the functions
	//but do not insert data into users table
	if ($argumentsArray["dry_run"]=="dry_run"){
		if ($argumentsArray["file"]==""){
			echo "the argument [--file] should be input with [dry_run]\n";
			exitWithHelpInfo();
		}else {
				$file = $argumentsArray["file"];
				$usersArray = getUsersFromFile($file);
				var_dump($usersArray);
				$isRunInsertDB = false;
				if ($argumentsArray["h"]==""||$argumentsArray["u"]==""){
					echo "please input the [-u] and [-h] arguments\n";
					exitWithHelpInfo();
				}else {
					usersInsertIntoDB($usersArray,$argumentsArray,$isRunInsertDB);
					exit(1);
				}
				
		}
	}

	//the input arguments do not contain "--help","create_table", "dry_run" arguments
	//and contain "--file" arguments, read all the users from file and insert into DB;
	if ($argumentsArray["file"]!=""){
		$file = $argumentsArray["file"];
		$usersArray = getUsersFromFile($file);
		if ($argumentsArray["h"]==""||$argumentsArray["u"]==""){
			echo "please input the [-u] and [-h] arguments\n";
			exitWithHelpInfo();
		}else {
				usersInsertIntoDB($usersArray,$argumentsArray,$isRunInsertDB);
				exit(1);
		}
	}
	
	// command line parse function, parse the command line and store all the arguments
	// in the $argumentsArray and return.
	// input parameters:
	// $argc: the number of input arguments from command line
	// $argv: the array of input arguments from command line
	// return value:
	// $argumentsArray: the parsed arguments array
	function commandArgumentsParse($argc,$argv){
		$argumentsArray = array("file"=>"","create_table"=>"","dry_run"=>"","help"=>"","u"=>"","p"=>"","h"=>"");
		for ($i=1;$i<$argc;$i++){
			$argument = $argv[$i];
			if (substr($argument,0,1)!="-")
				continue;
			switch ($argument)
			{
				case "--help":
					$argumentsArray["help"] = "help";
					break;
				case "--file":
					$argumentsArray["file"] = $argv[$i+1];
					break;
				case "--create_table":
					$argumentsArray["create_table"] = "create_table";
					break;
				case "--dry_run":
					$argumentsArray["dry_run"] = "dry_run";
					break;
				case "-u":
					$argumentsArray["u"] = $argv[$i+1];
					break;
				case "-p":
					$argumentsArray["p"] = $argv[$i+1];
					break;
				case "-h":
					$argumentsArray["h"] = $argv[$i+1];
					break;
				default:
					echo "unknown option:".$argv[$i]."\n";
					exitWithHelpInfo();
			}

		}

		var_dump($argumentsArray);
		return $argumentsArray;
	}

	//the function will output the list of directives with details
	//and exit the php script
	function exitWithHelpInfo(){
		echo "--fine [csv file name] - this is the name of the CSV to be parsed.\n";
		echo "--create_table – this will cause the MySQL users table to be built (and no further\n"
			."action will be taken)\n";
		echo "--dry_run – this will be used with the --file directive in the instance that we want\n"
		."to run the script but not insert into the DB. All other functions will be executed,\n"
		."but the database won't be altered.\n";
		echo "-u – MySQL username\n";
		echo "-p – MySQL password\n";
		echo "-h – MySQL host\n";
		echo "--help – which will output the above list of directives with details.\n";
		exit(-1);
	}

	//read users.csv file function, read all users from file and store in an array
	//input parameter:
	//$fileName: the users file parsed from command line
	//return value:
	//$usersArray: the users array readed from file
	function getUsersFromFile($fileName){
		$usersArray = array();
		try {
				$fp = fopen($fileName,'rb');
				if ($fp){
					$index = 0;
					while(!feof($fp)){
						$line = fgets($fp);
						if (!empty($line)){
							if ($index>0){
								$usersArray[$index-1] = $line;
							}
    						$index++;
						}
					}
				}
				
		} catch (Exception $e){
			echo "read file failed:".$e->getMessage()."\n";
		}
		var_dump($usersArray);
		return $usersArray;
		
	}

	//create users table function, if the users table existed, drop it and recreate
	//input parameter:
	//$argumentsArray: the parsed arguments array from command line
	function usersTableCreate($argumentsArray){
		try {
				$host = $argumentsArray["h"];
				$dbuser = $argumentsArray["u"];
				$dbpass = $argumentsArray["p"];
				$db = new PDO("mysql:host=$host;dbname=test;charset=utf8",$dbuser,$dbpass);
				$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$sql = "drop table if exists users;
				    create table users(
					name varchar(50),
					surname varchar(50),
					email varchar(100),
					unique key (email)
					);";
				$db->exec($sql);
		} catch (PDOException $ex) {
			echo 'Database operation failed:'.$ex->getMessage()."\n";
		}
	}

	//insert users into database function, only the use which has valid email can
	//be inserted, the invalid email will be print in the sceen.
	//input parameters:
	//$usersArray: the users array readed from file
	//$argumentsArray:the parsed arguments array from command line
	//$isRunInsertDB: true: insert;false: not insert
	function usersInsertIntoDB($usersArray,$argumentsArray,$isRunInsertDB){
		try {
				$host = $argumentsArray["h"];
				$dbuser = $argumentsArray["u"];
				$dbpass = $argumentsArray["p"];
				$db = new PDO("mysql:host=$host;dbname=test;charset=utf8",$dbuser,$dbpass);
				echo count($usersArray)."\n";
				for ($i=0;$i<count($usersArray);$i++){
					//$user = array();
					$user = explode(",", $usersArray[$i]);
					if (count($user)!=3)
						echo "user info lack of fields";
					else{
							$name = addslashes(ucfirst(trim($user[0])));
							$surname = addslashes(ucfirst(trim($user[1])));
							$email =  strtolower(trim($user[2]));
							//echo $name.",".$surname.",",$email."\n";
							if (validateEmail($email)){
								echo "validated email:".$name.",".$surname.",",$email."\n";
								if ($isRunInsertDB){
									$email = addslashes($email);
									$sql = "insert into users values('$name','$surname','$email');";
									$db->exec($sql);
								}
								
							}else{
								echo "invalidated email:".$name.",".$surname.",",$email."\n";
							}

					}
				}
		} catch (PDOException $ex) {
			echo 'Database operation failed:'.$ex->getMessage()."\n";
		}
		
		
	}

	//email validation function 
	//input parameter:
	//$email: the email of user
	//return value:
	//true: valid email; false: invalid email
	function validateEmail($email){
		if (preg_match('/^([0-9a-zA-Z]([-!\.\w]*[0-9a-zA-Z][\'\!]*)*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/i', $email)) 
    		return true;
		else
			return false;
	}
?>