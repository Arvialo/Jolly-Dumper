<?php

$pass = "password";
$user= "tamanoir";
$host = "localhost";
$name= "arthur";

$pdo = new PDO("mysql:host=".$host.";dbname=".$name,$user,$pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['req'])){
    $req = $pdo->query($_GET['req'])->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($req);
}
else { ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jolly Dumper</title>
</head>
<body>
    <div id="controls">
        <button id="init" onclick="show_databases()">Initialize</button>
        <button id="sql" onclick="show_command()">SQL command</button>
        <button id="return" title="return" onclick="last_page()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.862 477.862"><path d="M187.722,102.856V17.062C187.719,7.636,180.076-0.003,170.65,0c-4.834,0.001-9.44,2.053-12.676,5.644L4.375,176.311 c-5.617,6.256-5.842,15.67-0.529,22.187l153.6,187.733c5.968,7.295,16.72,8.371,24.016,2.403c3.952-3.233,6.249-8.066,6.26-13.172 v-85.043c134.827,4.386,218.965,62.02,256.888,175.787c2.326,6.96,8.841,11.653,16.179,11.656c0.92,0.003,1.84-0.072,2.748-0.222 c8.256-1.347,14.319-8.479,14.319-16.845C477.855,259.818,356.87,112.174,187.722,102.856z" fill="#343435"/></svg></button>
    </div>

    <input id="command-line" type="text" onchange="sql_command()" placeholder="press enter to exec">

    <h1 id="command"></h1>
    <div id="output"></div>
    <script>
const output = document.querySelector("#output");
const command = document.querySelector("#command");
const commandLine = document.querySelector("#command-line");
const sql = document.querySelector("#sql");
const init = document.querySelector("#init");
var last_page = () => { init.style.display = 'block'; sql.style.display = 'block'; output.innerHTML = ''; command.innerHTML = ''; commandLine.style.display = 'none'; };
function show_command() {
    init.style.display = 'none';
    sql.style.display = 'none';
    commandLine.style.display = 'block';
}
function sql_command() {
    fetch(`index.php?req=${commandLine.value}`)
    .then(res => {
        if (res.status == 200) {
            res.json().then(data => {
                HTML = '<table id="content">';
                console.log(data)
                data.forEach(elem => {
                    HTML += "<tr>"
                    for (key in elem) {
                        HTML += `<th>${key}</th><td>${elem[key]}</td>`;
                    }
                    HTML += "</tr>";
                })
                HTML += "</table>";
                output.innerHTML = HTML;
            })
        }
        else {
            alert('invalid request');
        }
    })
}
function show_databases() {
    init.style.display = 'none';
    sql.style.display = 'none';
    fetch(`index.php?req=SHOW DATABASES`)
    .then(res => res.json())
    .then(data => {
        command.innerHTML = "SHOW DATABASES"; 
        HTML = "<table>";
        data.forEach(db => {
            HTML += `<tr><td><button onclick="show_tables('${db['Database']}')">${db['Database']}</button></td></tr>`;
        })
        HTML += "</table>";
        output.innerHTML = HTML;
    })
    last_page = () => { init.style.display = 'block'; sql.style.display = 'block'; output.innerHTML = ''; command.innerHTML = ''; commandLine.style.display = 'none'; };
}
function show_tables(database) {
    fetch(`index.php?req=SHOW TABLES FROM ${database}`)
    .then(res => res.json())
    .then(data => {
        command.innerHTML = `SHOW TABLES FROM ${database}`; 
        HTML = "<table>";
        data.forEach(table => {
            HTML += `<tr><td><button onclick="show_columns('${database}','${table["Tables_in_"+database]}')">${table["Tables_in_"+database]}</button></td></tr>`;
        })
        HTML += "</table>";
        output.innerHTML = HTML;
    })
    last_page = () => { show_databases() };
}
function show_columns(database,table) {
    fetch(`index.php?req=SELECT * FROM ${database}.${table}`)
    .then(res => res.json())
    .then(data => {
        fetch(`index.php?req=DESCRIBE ${database}.${table}`)
        .then(res => res.json())
        .then(data2 => {
            HTML = '<table id="content">';
            command.innerHTML = `SELECT * FROM ${database}.${table}`;
            HTML += "<tr>";
            data2.forEach(field => {
                HTML += `<th title="${field['Type']}">${field['Field']}</th>`;
            })
            HTML += "</tr>";
            data.forEach((column,i) => {
                HTML += "<tr>";
                for (const key in column) {
                    HTML += `<td>${column[key]}</td>`;
                }
                HTML += "</tr>";
            })
            HTML += "</table>";
            output.innerHTML = HTML;
        })
    })
    last_page = () => { show_tables(database) };
}
    </script>
    <style>
:root {
    --red: #e63946;
    --white: #eeeffa;
    --light: #a8addc;
    --blue: #457b9d;
    --marine: #1d3557;
    --black: #343435;
    --green: #408a53;
    --green-d: #21522d;
}
html {
    font-family: Arial, Helvetica, sans-serif;
    background-color: var(--white);
}
body {
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}
button {
    appearance: unset;
    border: none;
    background: none;
}
#controls {
    margin: .2rem;
    border-radius: .2rem;
    overflow: hidden;
    display: flex;
}
#controls button {
    margin: .5rem;
    color: white;
    font-size: 1.4rem;
    cursor: pointer;
}
#sql {
    background-color: var(--green);
    border-radius: 35px;
    padding: 5px 15px;
    transition: background-color .1s ease;
}
#sql:hover {
    background-color: var(--green-d);
}
#init {
    background-color: var(--blue);
    border-radius: 35px;
    padding: 5px 15px;
    transition: background-color .1s ease;
}
#init:hover {
    background-color: var(--marine);
}
#return svg {
    width: 2.5rem;
}
#command {
    font-size: 1.2rem;
    text-align: center;
    color: var(--black);
    margin: 1.5rem 0 1rem;
    background-color: white;
    border-radius: 4px;
    padding: 2px 4px;
}
#command:empty {
    padding: 0;
}
table:not(#content),table:not(#content) *:not(button),table:not(#content) *:not(td) {
    border-radius: 10px;
    border-spacing: 10px;
}
#content,#content *:not(button),#content *:not(td) {
    border: solid thin black;
    border-collapse: collapse;
    padding: 10px;
    font-size: 1rem;
}
table button {
    font-size: 1.4rem;
    color: white;
    padding: 10px;
    background-color: var(--light);
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: background-color .1s ease;
}
table button:hover {
    background-color: var(--red);
}
#output {
    max-width: 90vw;
    overflow-x: auto;
}
#command-line {
    display: none;
    font-size: 1.8rem;
    padding: 10px;
    border: solid 2px var(--black);
    border-radius: .4rem;
}
    </style>
</body>
</html>
<?php } ?>