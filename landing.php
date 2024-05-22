<?php include "../inc/dbinfo.inc"; ?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Blacklisted</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <?php
                /* Connect to PostgreSQL and select the database. */
                $constring = "host=" . DB_SERVER . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD ;
                $connection = pg_connect($constring);

                if (!$connection){
                echo "Failed to connect to PostgreSQL";
                exit;
                }

                /* Ensure that the Customers table exists. */
                VerifyCustomersTable($connection, DB_DATABASE);

                /* If input fields are populated, add a row to the CUSTOMERS table. */
                $customer_name = htmlentities($_POST['NAME']);
                $customer_email = htmlentities($_POST['EMAIL']);

                if (strlen($customer_name) || strlen($customer_email)) {
                AddCustomer($connection, $customer_name, $customer_email);
                header('Location: http://ec2-18-212-92-145.compute-1.amazonaws.com/thankyou.html');
                }
            ?>

            <!-- Display table data. 
            <table border="1" cellpadding="2" cellspacing="2">
            <tr>
                <td>ID</td>
                <td>NAME</td>
                <td>EMAIL</td>
            </tr> -->
            
            <?php
            /*
            $result = pg_query($connection, "SELECT * FROM CUSTOMERS");

            while($query_data = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>",$query_data[0], "</td>",
                "<td>",$query_data[1], "</td>",
                "<td>",$query_data[2], "</td>";
            echo "</tr>";
            } */
            ?> 
            </table>
            <div class="headerTop">
                <h1 id="welcomeOne">Welcome to</h1>
            </div>
            <div class="headerBottom">
                <h1>Blacklisted</h1>
		<div class="directory">
			<a>About</a>
			<a>Models</a>
			<a>Connect With Us</a>
			<a>Create With Us</a>
		</div>
                            <!-- Input form -->
                <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                <table style="border:0">
                    <tr>
                    <td>
                    <input type="text" placeholder="Please enter your name to signup for our newsletter." name="NAME" maxlength="45" size="30" />
                    </td>
                    <td>
                    <input type="text" placeholder="Please enter your e-mail to signup for our newsletter." name="EMAIL" maxlength="90" size="60" />
                    </td>
                    <td>
                    <input id="formButton" type="submit" value="Submit" />
                    </td>
                    </tr>
                </table>
                </form>
            </div>
            <!-- Clean up. -->
            <?php
                pg_free_result($result);
                pg_close($connection);
            ?>
        </body>
    </html>

    <?php

/* Add a customer to the table. */
function AddCustomer($connection, $name, $email) {
   $n = pg_escape_string($name);
   $a = pg_escape_string($email);
   $query = "INSERT INTO CUSTOMERS (NAME, EMAIL) VALUES ('$n', '$a');";

   if(!pg_query($connection, $query)) echo("<p>Error adding customer data.</p>"); 
}

/* Check whether the table exists and, if not, create it. */
function VerifyCustomersTable($connection, $dbName) {
  if(!TableExists("CUSTOMERS", $connection, $dbName))
  {
     $query = "CREATE TABLE CUSTOMERS (
         ID serial PRIMARY KEY,
         NAME VARCHAR(45),
         EMAIL VARCHAR(90)
       )";

     if(!pg_query($connection, $query)) echo("<p>Error creating table.</p>"); 
  }
}
/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = strtolower(pg_escape_string($tableName)); //table name is case sensitive
  $d = pg_escape_string($dbName); //schema is 'public' instead of 'sample' db name so not using that

  $query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t';";
  $checktable = pg_query($connection, $query);

  if (pg_num_rows($checktable) >0) return true;
  return false;

}
?>
