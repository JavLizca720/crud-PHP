<?php include("template/header.php"); ?>
<?php 
//print_r($_POST);

$id=(isset($_POST['id']))?$_POST['id']:"";
$name=(isset($_POST['name']))?$_POST['name']:"";
$mail=(isset($_POST['mail']))?$_POST['mail']:"";
$job=(isset($_POST['job']))?$_POST['job']:"";
$cel=(isset($_POST['cel']))?$_POST['cel']:"";
$salary=(isset($_POST['salary']))?$_POST['salary']:"";
$photo=(isset($_FILES['photo']['name']))?$_FILES['photo']['name']:"";

$action=(isset($_POST['action']))?$_POST['action']:"";


include("conection/conection.php");




switch($action){
    case"Add":
        //INSERT INTO `empresa`.`employees` (`id`, `nombre`, `email`, `id_job`, `cel`, `salary`, `photo`) VALUES ('1', 'ddd', 'ddd', '2', '311', '111', 'f');
        $senSQL= $conn->prepare("INSERT INTO `empresa`.`employees` (name, email, job, cel, salary, photo) VALUES (:name, :email, :job, :cel, :salary, :photo);");
        $senSQL->bindParam(':name',$name);
        $senSQL->bindParam(':email',$mail);
        $senSQL->bindParam(':job',$job);
        $senSQL->bindParam(':cel',$cel);
        $senSQL->bindParam('salary',$salary);

        $date= new DateTime();
        $nameFile=($photo!="")?$date->getTimestamp()."_".$_FILES["photo"]["name"]:"photo.jpg";
        
        $tmpPhoto=$_FILES["photo"]["tmp_name"];

        if($tmpPhoto!=""){
            move_uploaded_file($tmpPhoto,"uploads/".$nameFile);
        }

        $senSQL->bindParam(':photo',$nameFile);
        $senSQL->execute();
        break;
    
    case"Mod":
        $senSQL = $conn->prepare("UPDATE employees SET name=:name WHERE id=:id");
        $senSQL->bindParam(':name',$name);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();


        $senSQL = $conn->prepare("UPDATE employees SET email=:mail WHERE id=:id");
        $senSQL->bindParam(':mail',$mail);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();

        $senSQL = $conn->prepare("UPDATE employees SET job=:job WHERE id=:id");
        $senSQL->bindParam(':job',$job);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();

        $senSQL = $conn->prepare("UPDATE employees SET cel=:cel WHERE id=:id");
        $senSQL->bindParam(':cel',$cel);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();

        $senSQL = $conn->prepare("UPDATE employees SET salary=:salary WHERE id=:id");
        $senSQL->bindParam(':salary',$salary);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();

        if($photo!=""){ 
            
            $date= new DateTime();
            $nameFile=($photo!="")?$date->getTimestamp()."_".$_FILES["photo"]["name"]:"photo.jpg";
    
            $tmpPhoto=$_FILES["photo"]["tmp_name"];
    
            move_uploaded_file($tmpPhoto,"uploads/".$nameFile);
            
            $senSQL = $conn->prepare("SELECT photo FROM employees WHERE id=:id");
            $senSQL->bindParam(':id',$id);
            $senSQL->execute();
            $employee=$senSQL->fetch(PDO::FETCH_LAZY);
    
            if(isset($employee["photo"]) &&($employee["photo"]!="photo.jpg")){
                if(file_exists("uploads/".$employee["photo"])){
                    unlink("uploads/".$employee["photo"]);
                }
            }
             $senSQL = $conn->prepare("UPDATE employees SET photo=:photo WHERE id=:id");
             $senSQL->bindParam(':photo',$nameFile);
             $senSQL->bindParam(':id',$id);
             $senSQL->execute();

        

        $senSQL = $conn->prepare("UPDATE employees SET cel=:cel WHERE id=:id");
        $senSQL->bindParam(':cel',$cel);
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();
        }
        break;

    case"Can":
        header("Location:crud.php");
        //echo('CANCELAR');
        break;

    case"Select":
        $senSQL = $conn->prepare("SELECT * FROM employees WHERE id=:id");
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();
        $employee=$senSQL->fetch(PDO::FETCH_LAZY);

        $name=$employee['name'];
        $mail=$employee['email'];
        $job=$employee['job'];
        $cel=$employee['cel'];
        $salary=$employee['salary'];
        $photo=$employee['photo'];
        break;

    case"Drop":

        $senSQL = $conn->prepare("SELECT photo FROM employees WHERE id=:id");
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();
        $employee=$senSQL->fetch(PDO::FETCH_LAZY);

        if(isset($employee["photo"]) &&($employee["photo"]!="photo.jpg")){
            if(file_exists("uploads/".$employee["photo"])){
                unlink("uploads/".$employee["photo"]);
            }
        }

        
        $senSQL = $conn->prepare("DELETE FROM employees WHERE id=:id");
        $senSQL->bindParam(':id',$id);
        $senSQL->execute();
        
        break;
}

$senSQL = $conn->prepare("SELECT * FROM employees");
$senSQL->execute();
$listaE=$senSQL->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="col-md-3">

<div class="card">
    <div class="card-header">
        Datos de empleados
    </div>
    <div class="card-body">
        
    
    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for"id">ID</label>
            <input type="text" class="form-control" value="<?php echo $id?>" name="id" id="id" placeholder="ID" required readonly>
        </div>
    
        <div class="form-group">
            <label for"name">Nombre</label>
            <input type="text" class="form-control" value="<?php echo $name?>"  name="name" id="name" placeholder="Nombre" required >
        </div>

        <div class="form-group">
            <label for"mail">Correo</label>
            <input type="text" class="form-control" value="<?php echo $mail?>"  name="mail" id="mail" placeholder="Correo" required >
        </div>

        <div class="form-group">
            <label for"job">Cargo</label>
            <input type="text" class="form-control" value="<?php echo $job?>"  name="job" id="job" placeholder="Cargo" required >
        </div>

        <div class="form-group">
            <label for"cel">Celular</label>
            <input type="number" class="form-control" value="<?php echo $cel?>"  name="cel" id="cel" placeholder="Celular" required >
        </div>

        <div class="form-group">
            <label for"salary">Salario</label>
            <input type="number" class="form-control" value="<?php echo $salary?>" name="salary" id="salary" placeholder="Salario" required >
        </div>

        <div class="form-group">
            <label for"photo">Foto</label>


            <br>
            <?php 
             if($photo!=""){ ?>
            <img src="uploads/<?php echo $photo ?>" width="50px" height="50px">
             <?php }
            ?>
            <input type="file" class="form-control" value="<?php echo $photo?>" name="photo" id="photo" placeholder="Foto" >
        </div>

        <div class="btn-group" role="group" arial-label="">
            <button type="submit" name="action" value="Add" <?php echo ($action=="Select")?"disabled":""?> class="btn btn-success">Agregar</button>
            <br>
            <button type="submit" name="action" value="Mod" <?php echo ($action!="Select")?"disabled":""?> class="btn btn-warning">Modificar</button>
            <br>
            <button type="submit" name="action" value="Can" <?php echo ($action!="Select")?"disabled":""?> class="btn btn-info">Cancelar</button>
        </div>
    </form>

    </div>

</div>


    
</div>

<div class="col-md-7">
    
<table class="table table-border">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Cargo</th>
            <th>Celular</th>
            <th>Foto</th>
            <th>Funciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($listaE as $empleado) {?>
        <tr>
            <td><?php echo $empleado['name'] ?></td>
            <td><?php echo $empleado['email'] ?></td>
            <td><?php echo $empleado['job'] ?></td>
            <td><?php echo $empleado['cel'] ?><td>
            <td><img src="uploads/<?php echo $empleado['photo'] ?>" width="50px" height="50px"></td>
            <td>
                <form method="POST">
                <input type="hidden" value="<?php echo $empleado['id']?>" name="id" id="id" />
                <button type="submit" name="action" value="Select" class="btn btn-primary">Seleccionar</button>
                <button type="submit" name="action" value="Drop" class="btn btn-danger">Borrar</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>


</div>

<?php include("template/footer.php"); ?>