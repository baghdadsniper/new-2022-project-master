<?php
ob_start();
session_start();
$pageTitle='Items';
if(isset($_SESSION['Username'])){
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage') {
        $stmt = $con->prepare("SELECT items.*, categories.Name AS category_name, users.Username FROM items
        INNER JOIN categories ON categories.ID = items.Cat_ID
        INNER JOIN users ON users.UserID = items.Member_ID");
        //execute statement
        $stmt->execute();

        //assign to vars
        $rows = $stmt->fetchAll();



?>
        <h1 class="text-center">
            manage items
        </h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>description</td>
                        <td>price</td>
                        <td>adding date</td>
                        <td>Category</td>
                        <td>username</td>
                        <td>control</td>
                    </tr>
                    <?php
                    foreach ($items as $item) {
                        echo "<tr>";
                        echo "<td>" . $item['Item_ID'] . "</td>";
                        echo "<td>" . $item['Name'] . "</td>";
                        echo "<td>" . $item['Description'] . "</td>";
                        echo "<td>" . $item['Price'] . "</td>";
                        echo "<td>" . $item['Add_Date'] . "</td>";
                        echo "<td>" . $item['category_name'] . "</td>";
                        echo "<td>" . $item['Username'] . "</td>";
                        echo "<td>
                           <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>edit</a>
                           <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>delete</a>";
                           if ($item['Approve'] == 0){
                            echo  "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>approve</a>";
                            }
                           echo "/td>";
                        echo "</td>";
                    }
                    ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> new item</a>
        </div>


    <?php 
    }elseif ($do == 'Add'){
        ?>
        <h1 class="text-center">
            add new item
        </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="name" class="form-control" required="required" placeholder="name of the item">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="description" class="form-control" placeholder="description of the item">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">price</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="price" class="form-control" required="required" placeholder="price of the item">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">country</label>
                    <div class="col-sm-10 col-md-4 ">
                        <input type="text" name="country" class="form-control" required="required" placeholder="country of made">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">status</label>
                    <div class="col-sm-10 col-md-4 ">
                        <select name="status" id="">
                            <option value="0">...</option>
                            <option value="1">new</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">very old</option>
                        </select>
                </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">member</label>
                    <div class="col-sm-10 col-md-4 ">
                        <select name="member" id="">
                            <option value="0">...</option>
                            <?php 
                            $stmt = $con->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach($users as $user){
                               echo "<option value='" . $user['UserID'] ."'>" . $user['Username'] ."</option>";
                            }
                            ?>
                        </select>
                </div>
                </div>
                <div class="form-group-lg">
                    <label for="" class="col-sm-2 control-label">category</label>
                    <div class="col-sm-10 col-md-4 ">
                        <select name="category" id="">
                            <option value="0">...</option>
                            <?php 
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach($cats as $cat){
                               echo "<option value='" . $cat['ID'] ."'>" . $cat['Name'] ."</option>";
                            }
                            ?>
                        </select>
                </div>
                </div>
                <div class="form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                        <input type="submit" name="submit" value="Add item" class="btn-sm btn-primary ">
                    </div>
                </div>
            </form>
        </div>
        <?php
    }elseif ($do == 'Insert'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>insert item</h1>";
            echo "<div class='container'>";
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];

            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'name cant be <strong>empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'desc cant be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'price cant be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'country cant be <strong>empty</strong>';
            }
            if ($status == 0) {
                $formErrors[] = 'you must choose <strong>status</strong>';
            }if ($member == 0) {
                $formErrors[] = 'you must choose <strong>member</strong>';
            }if ($cat == 0) {
                $formErrors[] = 'you must choose <strong>category</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            //check if there is no errors proceed the update operation
            if (empty($formErrors)) {
                    //insert into the database
                    $stmt = $con->prepare("INSERT INTO
                items(Name, Description, Price, Country_Made, Status, Cat_ID, Member_Id, Add_Date)
                VALUES(:zname, :zdesc, :zprice, :zname, :zcountry, :zstatus ,:zcat , :zmember now())");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zcat' => $cat,
                        'zmember' => $member
                    ));
                    //echo success message
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record inserted</div>';

                    redirectHome($theMsg);
                    echo "</div>";
                }
            }
        else {
            $theMsg = 'sorry you cant browse this page directly';
            redirectHome($theMsg, 'back', 4);
        }
    }elseif ($do == 'Edit'){
        $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {
            ?>
            <h1 class="text-center">
                edit item
            </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php 
                           echo $item['Item_ID'] ?>">
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="name" class="form-control" required="required" placeholder="name of the item" value="<?php 
                           echo $item['Name'] ?>">
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="description" class="form-control" placeholder="description of the item" <?php 
                           echo $item['Description'] ?>>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">price</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="price" class="form-control" required="required" placeholder="price of the item"<?php 
                           echo $item['Price'] ?>>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">country</label>
                        <div class="col-sm-10 col-md-4 ">
                            <input type="text" name="country" class="form-control" required="required" placeholder="country of made"<?php 
                           echo $item['Country_Made'] ?>>
                        </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">status</label>
                        <div class="col-sm-10 col-md-4 ">
                            <select name="status" id="">
                                <option value="1" <?php if($item['Status'] == 1){echo 'selected';}?>>new</option>
                                <option value="2" <?php if($item['Status'] == 2){echo 'selected';}?>>like new</option>
                                <option value="3" <?php if($item['Status'] == 3){echo 'selected';}?>>used</option>
                                <option value="4" <?php if($item['Status'] == 4){echo 'selected';}?>>very old</option>
                            </select>
                    </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">member</label>
                        <div class="col-sm-10 col-md-4 ">
                            <select name="member" id="">
                                <?php 
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                   echo "<option value='" . $user['UserID'] . "'"; if($item['Member_ID'] == $user['UserID']){echo 'selected';} echo ">" . $user['Username'] ."</option>";
                                }
                                ?>
                            </select>
                    </div>
                    </div>
                    <div class="form-group-lg">
                        <label for="" class="col-sm-2 control-label">category</label>
                        <div class="col-sm-10 col-md-4 ">
                            <select name="category" id="">
                                <?php 
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach($cats as $cat){
                                   echo "<option value='" . $cat['ID'] . "'"; if($item['Cat_ID'] == $cat['ID']){echo 'selected';} ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                    </div>
                    </div>
                    <div class="form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10 col-md-4 ">
                            <input type="submit" name="submit" value="save item" class="btn-sm btn-primary ">
                        </div>
                    </div>
                </form>
            </div>
            <?php
    
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">there is no such id</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif ($do == 'Update'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update item</h1>";
            echo "<div class='container'>";
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];
            /*$id,
            $name,
            $desc,
            $price,
            $country,
            $status,
            $member,
            $cat*/
            // validate the form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'name cant be <strong>empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'desc cant be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'price cant be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'country cant be <strong>empty</strong>';
            }
            if ($status == 0) {
                $formErrors[] = 'you must choose <strong>status</strong>';
            }if ($member == 0) {
                $formErrors[] = 'you must choose <strong>member</strong>';
            }if ($cat == 0) {
                $formErrors[] = 'you must choose <strong>category</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }


            //check if there is no errors proceed the update operation
            if (empty($formErrors)) {

                //update the database

                $stmt = $con->prepare("UPDATE 
                items 
                    SET 
                        Name = ?, 
                        Description = ?, 
                        Price = ?,
                        Country_Made = ?,
                        Status = ?,
                        Cat_ID = ?,
                        Member = ?
                    WHERE 
                        Item_ID = ?");
                $stmt->execute(array($name,$desc,$price,$country,$status,$member,$cat));

                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
                redirectHome($theMsg, 'back', 4);
            }
        } else {
            $theMsg = '<div class="alert alert-danger">sorry you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif ($do == 'Delete'){
        echo "<h1 class='text-center'>delete item</h1>";
        echo "<div class='container'>";
        //delete item
        $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('Item_ID', 'items', $itemid);
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
            $stmt->bindParam(":zid", $itemid);
            $stmt->execute();
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record deleted</div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';

            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif ($do == 'Approve'){
        echo "<h1 class='text-center'>approve item</h1>";
        echo "<div class='container'>";
        //approve item
        $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('itemid', 'items', $itemid);
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
            //echo success message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record updated</div>';
            redirectHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';

            redirectHome($theMsg);
        }
        echo "</div>";
    }
    include $tpl . 'footer.php';

}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>