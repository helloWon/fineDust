<?php
  $mysqli = new mysqli('localhost', 'root', '', 'db_dust');
    if($mysqli->connect_error) {
            die('Connect Error:('.$mysqli->connect_errno.')'.$mysqli->connect_error);
    }

#    print "mysql connected!";
?>
