<?php include("template/header.php");
include("conection/conection.php");

$senSQL = $conn->prepare("SELECT * FROM employees");
$senSQL->execute();
$listaE=$senSQL->fetchAll(PDO::FETCH_ASSOC);
?>
<?php foreach($listaE as $empleado) {?>
<div class="col-md-3">

<div class="card">
    <img class="card-img-top" src="uploads/<?php echo $empleado['photo'] ?>" height="350rem" alt="">
    <div class="card-body">
        <h4 class="card-title"><?php echo $empleado['name'] ?></h4>
        <p> <?php echo $empleado['email'] ?></p>
        <p> <?php echo $empleado['cel'] ?></p>
        <p> <?php echo $empleado['job'] ?></p>
    </div>
</div>

</div>
<?php }?>

<?php include("template/footer.php"); ?>