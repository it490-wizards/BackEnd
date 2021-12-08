<?php

// testing if we can run a shell command in php 
// probably not the best idea but there is a purpose for it here LOL
// the goal is to run a bash script whenever there is a certain named request for it on the server side

// 1st test the ability to run a shell command 

function test()
{
    $command = exec('ls -a'); // the parameter for exec is ('command argument, store the data from the return statement in an array')
    echo $command . PHP_EOL;
}


test();
