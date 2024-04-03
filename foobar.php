<?php 

//Loop from 1 to 100 
for($i=1; $i <= 100; $i++){ 
    // if the number is divisible by 3 and 5 output foobar 
    if($i % 3 == 0 && $i % 5 == 0){
        echo "foobar \n";      
    } 
    // if the number is divisible by 3 output foo 
    else if ($i % 3 == 0){ 
        echo "foo \n"; 
    } 
    // if the number is divisible by 5 output bar 
    else if($i % 5 == 0){
        echo "bar \n"; 
    } 
    // if the number is not divisible by (3 and 5) or (3 or 5) output the number itself 
    else{ 
        echo $i . "\n"; 
    }
} 

?> 