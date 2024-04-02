<?php

// Function to create MYSQL users table 
function createTable($conn) {
    $table = " CREATE TABLE IF NOT EXISTS users(
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255) NOT NULL, 
        surname VARCHAR(255) NOT NULL, 
        email VARCHAR(255) NOT NULL UNIQUE
        )"; 
    //users table created 
    if ($conn->query($table) === TRUE){
        echo "Users table created/rebuilt"; 
    } 
    //users table not created due to connection problems 
    else{
        echo "Error creating users table:" . $conn->error ."\n"; 
        exit(1); 

    }
} 


//Function for validating email 
function validateEmail($email){
    return filter_var($email,FILTER_VALIDATE_EMAIL); 
} 

//function to print the command line instructions  
function commandlineinstruct(){
    echo "--file [csv file name]      – this is the name of the CSV to be parsed"; 
    echo "--create_table             – this will cause the MySQL users table to be built (and no further action will be taken)"; 
    echo "--dry_run                 – this will be used with the --file directive in case we want to run the script but not insert
                                     into the DB. All other functions will be executed, but the database won't be altered";   
    echo "-u                       – MySQL username"; 
    echo "-p                       – MySQL password"; 
    echo "-h                       – MySQL host"; 
    
} 

$options = getopt("u:p:h",["--file","--create-table","--dry-run","help"]);  

//if its the help option the commanndlineinstruct function will be executed 
if (isset($options['help'])){
    commandlineinstruct();
} 



//reads the CSV file if the command is file  
$csv = $options["--file"];
$csv_file = array_map('str_getcsv', file($csv)); 


$Dryrun = isset($options["--dry-run"]); 
//If it is not dry run we create users table 
if (!Dryrun){
    createTable($conn); 
} 

foreach($csv_file as $row){
    //To capitalise first letter of name 
    $name = ucfirst(strtolower($row[0])); 
    //To capitalise first letter of surname 
    $surname = ucfirst(strtolower($row[1])); 
    //Change email to lowercase 
    $email = strtolower($row[2]); 
    
    //Validating Email 
    if(validateEmai[$email]){
        if(!Dryrun){
            $InsertToSql = "INSERT INTO users(name, surname, email) VALUES ($name, $surname, $email)";
            if($conn->query($InsertToSql) !== TRUE) {
                echo "Error inserting " . $conn->error . "\n";  
            }
        } 
        echo "Record inserted";  
    }
    else{ 
        echo "the email address is invalid";  
    } 
}