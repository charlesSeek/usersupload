<?php
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
?>