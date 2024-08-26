<?php
              function dbCon() {
				$host =		"localhost";
                $user =		"root";
                $passwd =	"";
                $db =		"mossdmls_tyreShop";
				$con = mysqli_connect($host,$user,$passwd,$db);
				$con->set_charset("utf8mb4");
				return $con;
			  }
               ?>