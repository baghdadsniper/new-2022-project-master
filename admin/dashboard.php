<?php
ob_start();
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'dashboard';
    include 'init.php';
    /*start dashboard page*/
    $latestUsers = 5;
    $theLatest = getLatest("*", "users", "UserID", $latestUsers);
?>
    <div class="home-stats">
        <div class="container text-center">
            <h1>dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        total Members
                        <span><a href="members.php"><?php echo countItems('UserID','users')?></a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("RegStatus", "users", 0)?></a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        total items
                        <span>200</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        total comments
                        <span>200</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <?php 
                        $latestUsers = 5;
                        ?>
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>latest <?php echo $latestUsers?> registered users
                        </div>
                        <div class="panel-body">
                            <?php
                                $theLatest = getLatest("*", "Users", "UserID", $latestUsers);
                                foreach ($theLatest as $user) {
                                    echo $user['Username'] . '<br>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>latest items
                        </div>
                        <div class="panel-body">
                            test
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

    /*end dashboard page*/
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>