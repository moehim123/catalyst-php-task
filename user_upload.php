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
    echo "--file [csv file name] – this is the name of the CSV to be parsed"; 
    echo "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)"; 
    echo "--dry_run – this will be used with the --file directive in case we want to run the script but not insert
    into the DB. All other functions will be executed, but the database won't be altered";   
    echo "-u – MySQL username"; 
    echo "-p – MySQL password"; 
    echo "-h – MySQL host"; 
    
}