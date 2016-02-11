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
?>