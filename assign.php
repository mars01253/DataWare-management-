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
if ($_SESSION['type'] === "scrummaster") {
    $scrmid = $_SESSION['id'];
    $equipe_id = $_SESSION['idequipe'];
    $sql = "SELECT project_id , project_name FROM projects WHERE scrum_master_id= ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $scrmid);
    mysqli_stmt_bind_result($stmt, $project_id, $projectname);
    $projects = [];

    while (mysqli_stmt_fetch($stmt)) {
        $projects[$project_id] = $projectname;
    }

    echo "
    <form action='assign.php' method='get' class='bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5'>
        <select name='team' placeholder='Change the Team Name'>";

    foreach ($projects as $id => $name) {
        echo "<option value='$id'>$name</option>";
    }

    echo "</select>
        <input type='submit' name='submit' value='Done'>
    </form>";

    if (isset($_GET['submit']) && !empty($_GET['team'])) {
        $new_team_name = $_GET['team'];
        $sql2 = "UPDATE equipe SET equipe_name=? WHERE scrum_master_id=? AND equipe_id=?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "sii", $new_team_name, $scrmid, $equipe_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }
}




if ($_SESSION['type'] === "member") {
    $scrummaster = $_SESSION['id'];
    $user_id = $_SESSION['userid'];
    $sql = "SELECT equipe_id, equipe_name , project_id FROM equipe WHERE scrum_master_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $scrummaster);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $equipe_id, $equipe_name, $project_id);
    $teamnames = [];

    while (mysqli_stmt_fetch($stmt)) {
        $teamnames[$equipe_id] = $equipe_name;
    }

    echo "<form action='assign.php' method='get' class='bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5'>
            <select name='selected_team'>
                <option disabled hidden selected>Choose a team</option>";

    foreach ($teamnames as $id => $name) {
        echo "<option value='$id'>$name</option>";
    }

    echo "</select>
            <input type='submit' name='submit' value='Assign Team'>
          </form>";
    if (isset($_GET['submit'])) {
        $selected_team = $_GET['selected_team'];
        $sql_update = "UPDATE users SET status = 'active', equipe_id = ?, project_id = ? WHERE user_id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);

        if ($stmt_update) {
            $userid = $_SESSION['userid'];
            mysqli_stmt_bind_param($stmt_update, "iii", $selected_team, $project_id, $userid);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
            mysqli_close($conn);
            header('location:scrum.php');
            exit();
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }
}


?>