<script src="https://cdn.tailwindcss.com"></script>
<?php

include("databaseconn.php");
session_start();
if ($_SESSION['type'] === "adm") {
    $id = $_GET['id'];
    $sql = " UPDATE users SET user_role='Product Owner' WHERE user_id=$id";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    header('location:adm.php');
}
if ($_SESSION['type'] === 'productowner') {
    $project_id = $_GET['idprj'];
    echo
    '<form action="assign.php" method="get" class="bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5">
    <input type="text" placeholder="project name" name="projectname" class="w-[60%] rounded-xl">
    <input type="text" placeholder="scrum master" name="scrummaster" class="w-[60%] rounded-xl">
    <input type="submit" value="modify project" name="submitproject" class="text-white">
    </form>';
    $projectname = $_GET['projectname'];
    $scrummaster = $_GET['scrummaster'];
    $sql = " UPDATE projects SET project_name= $projectname WHERE project_id= $project_id";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    header('location:productowner.php');
}
if ($_SESSION['type'] === "assignscrum") {
    if ($_SESSION['type'] === "assignscrum") {
        $prjid = $_SESSION['project'];
        $id = $_GET['idscrm'];
        $stmt = mysqli_prepare($conn, "UPDATE users SET project_id=?, status='active' WHERE user_id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $prjid, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('location: productowner.php');
            exit();
        }
    }
}
?>