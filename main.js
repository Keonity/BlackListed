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
await client.end();

/* emailField.addEventListener("change", (event) => {
    let userEmail = document.getElementById('email').value;
    let welcomeMsg = document.querySelector('#welcomeOne');
    welcomeMsg.textContent = `Welcome ${event.target.id} to`;
    client.end();
    console.log("Test");
}); */