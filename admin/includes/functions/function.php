<?php
//title function that echo the page title
function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'default';
    }
}

//redirect function

function redirectHome($theMsg, $url = null, $seconds = 3)
{
    if ($url === null) {
        $url = 'index..php';
        $link = 'Homepage';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'previous page';
        }
        $url = 'index.php';
    }


    echo $theMsg;

    echo "<div class='alert alert-info'>you will be redirected to $link after $seconds seconds</div>";

    header("refresh:$seconds;url=$url ");

    exit();
}

//function to check items in database

function checkItem($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

//count number of items

function countItems($item, $table)
{
    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) From $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}

function getLatest($select, $table, $order, $limit = 5)
{
    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order desc $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();

    return $rows;
}
