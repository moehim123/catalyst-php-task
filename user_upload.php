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
    echo "--file [csv file name]      – this is the name of the CSV to be parsed \n"; 
    echo "--create_table             – this will cause the MySQL users table to be built (and no further action will be taken) \n"; 
    echo "--dry_run                 – this will be used with the --file directive in case we want to run the script but not insert
                                     into the DB. All other functions will be executed, but the database won't be altered \n";   
    echo "-u                       – MySQL username \n"; 
    echo "-p                       – MySQL password \n"; 
    echo "-h                       – MySQL host \n"; 
    
} 




function main(){ 
    
    $options = getopt("u:p:h",["file","create-table","dry-run","help"]);  

    //if its the help option the commanndlineinstruct function will be executed 
    echo json_encode(isset($options['help'])); 
    if (isset($options['help'])){
        commandlineinstruct();
        return; 
    } 

    $servename =$option['h'] ?? "localhost"; 
    $username =$option['u'] ?? "username"; 
    $password = $option['p'] ?? "password"; 
    $dbname = "catalyst_database";  

    $conn = new mysqli($servename,$username, $password, $dbname); 

    if ($conn -> connect_error){
        die("connection failed" . $conn->connect_error . "\n"); 

    }
    

    connectToDatabase(); 
    // create table 
    if (isset($options['reate-table'])){
        connectToDatabase(); 
        $conn->close(); 
        return; 
    } 
        
    //reads the CSV file if the command is file  
    $csv = $options["file"];
    $csv_file = array_map('str_getcsv', file($csv)); 


    $Dryrun = isset($options["dry-run"]); 

    InsertToDatabase($csv_file, $conn, $Dryrun); 

} 

function InsertToDatabase($csv_file, $conn,$Dryrun){  
    

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
} 
main(); 
?> 