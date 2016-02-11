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
?>