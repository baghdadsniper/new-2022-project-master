<?php
ob_start();
session_start();
$pageTitle='Items';
if(isset($_SESSION['Username'])){
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage') {

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
                        <select class="form-control" name="status" id="">
                            <option value="0">...</option>
                            <option value="1">new</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">very old</option>
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
        
    }elseif ($do == 'Edit'){
        
    }elseif ($do == 'Update'){
        
    }elseif ($do == 'Delete'){
        
    }elseif ($do == 'Approve'){
        
    }
    include $tpl . 'footer.php';

}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>