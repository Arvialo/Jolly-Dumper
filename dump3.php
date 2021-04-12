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
else
{ 
?>
<html>
<input type="text" id="req" name="request" placeholder="SQL">
<div id="output">

</div>

<script>
var req = document.querySelector("#req");
var output = document.querySelector("#output");
req.addEventListener('input', () => {
    fetch(`dump3.php?req=${req.value}`)
    .then(resp => {
        if (resp.status == 500){
            output.innerHTML = "Invalid Request";
        }
        else {
            resp.json().then(
                data => {
                    output.innerHTML = "";
                    console.log(data);
                    data.forEach(elem => {
                        for (const key in elem){
                            output.innerHTML += `<h1>${key}</h1>------<h3>${elem[key]}</h3>`;
                        }
                    })
                }
            );
        }
    })
});
</script>

</html>
<?php } ?>