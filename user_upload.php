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
        echo "Users table created/rebuilt\n"; 
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
    if (isset($options['help'])){
        commandlineinstruct();
        return; 
    } 

    //Connecting to the database 
    $servename =$options['h'] ?? "localhost"; 
    $username =$options['u'] ?? "username"; 
    $password = $options['p'] ?? "password"; 
    $dbname = "catalyst_database";  

    $conn = new mysqli($servename,$username, $password, $dbname); 

    if ($conn -> connect_error){
        die("connection failed" . $conn->connect_error . "\n"); 

    }
    

   
    // create table 
    if (isset($options['create-table'])){ 
        createTable($conn); 
        $conn->close(); 
        return; 
    } 
        
    //reads the CSV file if the command is file  
    $csv = $options['file'] ?? "users.csv"; 
    $csv_file = fopen($csv , "r"); 


    $dryRun = isset($options["dry-run"]); 

    
  
    insertToDatabase($csv_file, $conn, $dryRun); 
    fclose($csv_file); 


} 

function insertToDatabase($csv_file, $conn,$dryRun){  
    

    while (($row = fgetcsv($csv_file, 1000, ",")) !== FALSE) {       
        $name = ucfirst(strtolower($row[0])); 
        //To capitalise first letter of surname 
        $surname = ucfirst(strtolower($row[1])); 
        //Change email to lowercase 
        $email = strtolower($row[2]); 
        $name = $conn->real_escape_string($name);
        $surname = $conn->real_escape_string($surname);
        $email = $conn->real_escape_string($email);
      
        
        //Validating Email 
        if(validateEmail($email)){ 
            if(!$dryRun){ 
                //this part is to check for any duplicates before inserting to database 
                $checkDuplicateSql = "SELECT COUNT(*) AS count FROM users WHERE email = '$email'";
                $result = $conn->query($checkDuplicateSql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    if ($row['count'] > 0) {
                        echo "User with email '$email' already exists.\n";
                    }
                    else{
                        //if the record is not duplicated insert it here 
                        $insertToSql = "INSERT INTO users(name, surname, email) VALUES ('$name', '$surname', '$email')";
                        if($conn->query($insertToSql) !== TRUE) {
        
                            echo "Error inserting " . $conn->error . "\n";  
                        }   
                        else{
                             echo "Record inserted\n";  
                        }
                    }
                }
               
                
            } 
            
        }
        else{ 
            echo "the email address is invalid" . $email . "\n";  
        } 
    } 
} 
main(); 
?> 

