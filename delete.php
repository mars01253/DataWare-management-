<?php
include("databaseconn.php");
session_start();

if ($_SESSION['type'] === 'adm') {
    $id = $_GET['id'];
    $project_id = $_GET['idprj'];
    $sql = "UPDATE users SET user_role='member' WHERE user_id=$id";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    header('location:adm.php');
}

if ($_SESSION['type'] === 'productowner') {
    $project_id = $_GET['idprj'];
    $managerid = $_SESSION['projectmanagerid'];
    $sql = "DELETE FROM projects WHERE project_id = $project_id ";
    mysqli_query($conn, $sql);
    $sqluser = "UPDATE users SET user_role = 'member', user_status = 'not active' , project_id = null WHERE user_id = ?";
    $stmtuser = mysqli_prepare($conn, $sqluser);
    mysqli_stmt_bind_param($stmtuser, "i", $managerid);
    mysqli_stmt_execute($stmtuser);
    mysqli_stmt_close($stmtuser);

    mysqli_close($conn);
    header('location:productowner.php');
}

if ($_SESSION['type'] === 'scrummaster') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $equipe_id = $_GET['id'];
    }
    $scrumid = $_SESSION['id'];
    $sql = "DELETE FROM equipe WHERE equipe_id =$equipe_id  AND scrum_master_id = $scrumid";
    mysqli_query($conn, $sql);
    $sqluser = "UPDATE users SET equipe_id=NULL WHERE user_id=$scrumid";
    mysqli_query($conn, $sqluser);
    $sqlproject = "UPDATE projects SET equipe_id=NULL WHERE scrum_master_id= $scrumid";
    mysqli_query($conn, $sqlproject);
    mysqli_close($conn);

    header('location:scrum.php');
}
if ($_SESSION['type'] === 'member') {
    $scrmid = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $userid = $_GET['iduser'];
    }

    $sql = "UPDATE users SET user_status = 'not active', project_id = NULL, equipe_id = NULL WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('location:scrum.php');
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

