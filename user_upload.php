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

