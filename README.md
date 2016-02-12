Script Description:
This php scritp is a demo project to parse command line, read users file, create table in mysql database and insert the users info into mysql table from users file.

Environment Assumption:
1. Ubuntu 14.04 linux
2. PHP  PHP 5.5.9
3. Mysql 5.6.25
4. the dedicated mysql database is "test" and the dedicated mysql table is "users"
(a better option is to add arguments -d [database] -t [table] in the command line)
5. all the codes are tested in Nectar/Openstack cloud.

User Guide:
1. copy the source code and users.csv file into directory or get files from github
2. execute $php user_upload.php [arguments]
3. the script will print all the result of different function in the sceen

Test Cases:
1. php user_upload.php --help //print all the help information
2. php user_upload.php --create_table -h localhost -u root -p root //create users table in mysql
3. php user_upload.php --file users.csv --dry_run -h localhost -u root -p root //execute all the functions except insert data into mysql
4. php user_upload.php --file users.csv -h localhost -u root -p root //execute read file and insert into database
5. other fault tolerant test
