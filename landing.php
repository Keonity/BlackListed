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

                /* Ensure that the EMPLOYEES table exists. */
                VerifyCustomersTable($connection, DB_DATABASE);

                /* If input fields are populated, add a row to the EMPLOYEES table. */
                $customer_name = htmlentities($_POST['NAME']);
                $customer_email = htmlentities($_POST['EMAIL']);

                if (strlen($customer_name) || strlen($customer_email)) {
                AddCustomer($connection, $customer_name, $customer_email);
                }
            ?>

            <!-- Input form -->
            <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                <table border="0">
                    <tr>
                    <td>NAME</td>
                    <td>EMAIL</td>
                    </tr>
                    <tr>
                    <td>
                    <input type="text" name="NAME" maxlength="45" size="30" />
                    </td>
                    <td>
                    <input type="text" name="EMAIL" maxlength="90" size="60" />
                    </td>
                    <td>
                    <input type="submit" value="Add Data" />
                    </td>
                    </tr>
                </table>
            </form>
            <!-- Display table data. -->
            <table border="1" cellpadding="2" cellspacing="2">
            <tr>
                <td>ID</td>
                <td>NAME</td>
                <td>EMAIL</td>
            </tr>

            <?php

            $result = pg_query($connection, "SELECT * FROM EMPLOYEES");

            while($query_data = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>",$query_data[0], "</td>",
                "<td>",$query_data[1], "</td>",
                "<td>",$query_data[2], "</td>";
            echo "</tr>";
            }
            ?>
            </table>
            <div class="headerTop">
                <h1 id="welcomeOne">Welcome to</h1>
            </div>
            <div class="headerBottom">
                <h1>Blacklisted</h1>
                <input type="text" placeholder="Please enter your e-mail to signup for our newsletter." name="email" id="email">
            </div>
            <!-- Clean up. -->
            <?php
                pg_free_result($result);
                pg_close($connection);
            ?>
        </body>
    </html>

    <?php

/* Add an employee to the table. */
function AddCustomer($connection, $name, $email) {
   $n = pg_escape_string($name);
   $a = pg_escape_string($email);
   echo "Forming Query";
   $query = "INSERT INTO EMPLOYEES (NAME, EMAIL) VALUES ('$n', '$a');";

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