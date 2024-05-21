import pg from 'pg';

const { Client } = pg;

const client = new Client({
    user: "postgres",
    password: "Mdjjc59KB79p9q6zPBv4",
    host: "database-1.cp4ycg8q4rm4.us-east-1.rds.amazonaws.com",
    port: "5432",
    database: "postgres"
});

await client.connect();
// let emailField = document.querySelector("#email");
// await client.query('CREATE TABLE testTable (num integer, name text);');

const text = 'INSERT INTO testTable(num, name) VALUES($1, $2) RETURNING *'
const values = [2, 'brian.m.carlson@gmail.com']
 
const res = await client.query(text, values)
console.log(res.rows[0])

await client.query('SELECT name from testTable');

await client.end();

/* emailField.addEventListener("change", (event) => {
    let userEmail = document.getElementById('email').value;
    let welcomeMsg = document.querySelector('#welcomeOne');
    welcomeMsg.textContent = `Welcome ${event.target.id} to`;
    client.end();
    console.log("Test");
}); */