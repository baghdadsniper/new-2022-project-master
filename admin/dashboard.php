<?php
ob_start();
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'dashboard';
    include 'init.php';
    /*start dashboard page*/
    $numUsers = 6;
    $latestUsers = getLatest("*", "users", "UserID", $numUsers);
    $numItems = 6;
    $latestItems = getLatest("*", "items", "Item_ID", $numItems);
?>
    <div class="home-stats">
        <div class="container text-center">
            <h1>dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                        total Members

                        <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                          </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                    <i class="fa fa-users-plus"></i>
                        <div class="info">
                        pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("RegStatus", "users", 0) ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                        <div class="info">
                            total items
                        <span><a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                        <div class="info">
                            total items
                        total comments
                        <span>200</span>
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
                        <?php
                        $numUsers = 5;
                        ?>
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>latest <?php echo $numUsers ?> registered users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                            $latestUsers = getLatest("*", "Users", "UserID", $numUsers);
                            foreach ($latestUsers as $user) {
                                echo $user['Username'];
                                echo '<li>';
                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit"></i> edit';
                                if($user['RegStatus'] == 0){
                                    echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class="btn btn-info pull-right activate><i class="fa fa-check"></i>Activate</a>"';
                                } 
                                echo '</span>';                     
                                echo '</a>';                     
                                echo '</li>';                     
                                  }
                            ?>
                            </ul>
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
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                            $latestItems = getLatest("*", "items", "Item_ID", $numItems);
                            foreach ($latestItems as $item) {
                                echo $Item['Name'];
                                echo '<li>';
                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit"></i> edit';
                                if($item['Approve'] == 0){
                                    echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-info pull-right activate><i class="fa fa-check"></i>approve</a>"';
                                } 
                                echo '</span>';                     
                                echo '</a>';                     
                                echo '</li>';                     
                                  }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>latest comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-comments-o"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php 
                                    $stmt = $con->prepare("SELECT 
                                    comments.*, users.Username AS Member
                                   FROM
                                    comments
                                     INNER JOIN
                                     users
                                     ON
                                     users.UserID = comments.user_id
                                    ");
          //excute statement
          $stmt->execute();
  
          //assign to vars
          $comments = $stmt->fetchAll();
          foreach ($comments as $comment){
            echo'<div class="comment-box">';
            echo '<span class="member-n">' . $comment['Member'] . '</span>';
            echo '<p class="member-c">' . $comment['comment'] . '</p>';
            echo'</div>';


          }
                            ?>


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