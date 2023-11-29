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
   
    if (isset($_GET['idprj'])) {
        $project_id = $_GET['idprj'];
    }
   
    
    echo
    '<form action="assign.php" method="get" class="bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5">
    <input type="text" placeholder="project name" name="projectname" class="w-[60%] rounded-xl">';
    $sql='SELECT user_id , user_fullname FROM users WHERE user_role="member" OR user_role="scrum master" ';
    $stmt=mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_result($stmt, $userid, $username);
    $potentialmanagers= [];
    while (mysqli_stmt_fetch($stmt)) {
        $potentialmanagers[$userid] = $username;
    }
    mysqli_stmt_close($stmt);
    
    echo "
    <form action='assign.php' method='get' class='bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5'>
        <select name='team' placeholder='Change the Team Name'>";

    foreach ($potentialmanagers as $userid => $username) {
        echo "<option value='$id'>$name</option>";
    }

    echo "</select>
        <input type='submit' name='submit' value='Done'>
    </form>";
   
    if(isset($_GET['projectname'])&&isset($_GET['scrummaster'])){
    $projectname = $_GET['projectname'];
    }if(isset($_GET['scrummaster'])){
        $scrummaster = $_GET['scrummaster'];
        }
    
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
    
    $sql = "SELECT project_id, project_name FROM projects WHERE scrum_master_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $scrmid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $project_id, $projectname);
    $projects = [];
    while (mysqli_stmt_fetch($stmt)) {
        $projects[$project_id] = $projectname;
    }

    mysqli_stmt_close($stmt);
    echo "<main class='w-[100%] h-[100%] flex items-center justify-center bg-slate-300'>
    <form action='assign.php' method='post' class='bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5'>
        <input type='text' name='changename'  class='w-[60%] rounded-xl' placeholder='enter your new team name'>
        <select name='project' class='w-[60%] rounded-xl'>
        <option class='w-[60%] rounded-xl' selected disabled hidden >Assign a new project</option>";

    foreach ($projects as $id => $name) {
        echo "<option class='w-[60%] rounded-xl' value='$id'>$name</option>";
    }

    echo "</select>
        <input type='submit' name='submit'  class='text-white cursor-pointer' value='Done'>
        <input type='submit' name='back'  class='text-white cursor-pointer' value='Back to main page'>
    </form> </main>";
    if (isset($_POST['submit']) && !empty($_POST['project'])&&!empty($_POST['changename'])) {
        $new_team_name = $_POST['changename'];
        $newproject=$_POST['project'];
        $sql2 = "UPDATE equipe SET equipe_name=? , project_id = ? WHERE scrum_master_id=? AND equipe_id=?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        
        if (!$stmt2) {
            die("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt2, "siii", $new_team_name, $newproject, $scrmid, $equipe_id);

        if (mysqli_stmt_execute($stmt2)) {
            echo "Team name updated successfully.";
        } else {
            echo "Error updating team name: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt2);
        header('location:scrum.php');
    }
    if (isset($_POST['submit']) && empty($_POST['project'])&&!empty($_POST['changename'])) {
        $new_team_name = $_POST['changename'];
        $sql2 = "UPDATE equipe SET equipe_name=? WHERE scrum_master_id=? AND equipe_id=?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        
        if (!$stmt2) {
            die("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt2, "sii", $new_team_name, $scrmid, $equipe_id);

        if (mysqli_stmt_execute($stmt2)) {
            echo "Team name updated successfully.";
        } else {
            echo "Error updating team name: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt2);
        header('location:scrum.php');
    }
    if (isset($_POST['submit']) && !empty($_POST['project'])&&empty($_POST['changename'])) {
        $new_team_name = $_POST['changename'];
        $newproject=$_POST['project'];
        $sql2 = "UPDATE equipe SET project_id = ? WHERE scrum_master_id=? AND equipe_id=?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        
        if (!$stmt2) {
            die("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt2, "iii",  $newproject, $scrmid, $equipe_id);

        if (mysqli_stmt_execute($stmt2)) {
            echo "Team name updated successfully.";
        } else {
            echo "Error updating team name: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt2);
        header('location:scrum.php');
    }
          
    if(isset($_POST['back'])){
        header('location:scrum.php');
        exit();
    }
}




if ($_SESSION['type'] === "member") {
    include("databaseconn.php");
    $scrummaster = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD']=='GET'){
        $userid=$_GET['iduser'];
    }

    $sql = "SELECT equipe_id, equipe_name , project_id FROM equipe WHERE scrum_master_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $scrummaster);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $equipe_id, $equipe_name, $project_id);
    $teamnames = [];
   
    while (mysqli_stmt_fetch($stmt)) {
        $teamnames[$equipe_id] = $equipe_name;
    }

    echo "<main class='w-[100%] h-[100%] flex items-center justify-center bg-slate-300'><form action='assign.php' method='post' class='bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5'>
            <select name='selected_team' class='w-[60%]'>
                <option disabled hidden selected>Choose a team</option>";

    foreach ($teamnames as $id => $name) {
        echo "<option value='$id'>$name</option>";
    }

    echo "</select>
            <input type='submit' name='back'  class='text-white cursor-pointer' value='Back to main page'>
            <input type='submit' name='submit' class='text-white cursor-pointer' value='Assign Team'>
            <input type='hidden' name='id' value='$userid'>
          </form></main>
          <script type='text/javascript' src='assignproblem.js'></script>";
          
    if(isset($_POST['back'])){
        header('location:scrum.php');
        exit();
    }
    if (isset($_POST['submit'])) {
        $userid= $_POST['id'];
        $selected_team = $_POST['selected_team'];
        $sql_update = "UPDATE users SET user_status = 'active', project_id = ?, equipe_id = ? WHERE user_id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);

        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "iii", $project_id, $selected_team,$userid);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
            mysqli_close($conn);
            header('location:scrum.php');
            exit();
        } 
    }
}


?>