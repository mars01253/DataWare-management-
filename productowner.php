<?php
session_start();
include("databaseconn.php");
if (!isset($_SESSION['id'])) {
  header('location:login.php');
  exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>

<body>
  <nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 ">
        <div class="flex items-center w-[100%] justify-between ">
          <div class="flex-shrink-0">
            <h1 class="text-white" >DataWare</h1>
          </div>
          <div class="hidden sm:block sm:ml-6 w-1/">
            <div class="flex space-x-4 ml-50 ">
              <form action="productowner.php" method="post" class="flex w-[100%]">
                <input type="submit" value="My Projects" name="projects" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                <input type="submit" value="Stats" name="stats" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                <a href="logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log out</a>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
    </div>
    </div>
    </div>
    <div class="sm:hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <form action="productowner.php" method="post">
          <input type="submit" value="My Projects" name="projects" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium">
          <input type="submit" value="Stats" name="stats" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
          <a href="logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log out</a>
        </form>
      </div>
    </div>
    </div>
  </nav>
  <section class=" bg-blue-50 w-[100vw] h-[100vh] absolute z-9 flex flex-col items-center justify-center hidden" id="projectpopup">
    <form action="productowner.php" method="get" class="bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5">
      <img src="img/close.svg" alt="..." class=" w-[6%] float-right mr-3 relative top-3 right-20 " id="closeform2">
      <input type="text" placeholder="project name" name="projectname" class="w-[60%] rounded-xl">
      <input type="text" placeholder="project description" name="projectdesc" class="w-[60%] rounded-xl">
      <select name="managers" id="managers" class="w-[60%] rounded-xl">
        <option value="choose your project manager" disabled selected hidden>choose your project manager</option>
        <?php
        $stmt = mysqli_prepare($conn, 'SELECT user_id , user_fullname FROM users WHERE user_role ="member" and user_status = "not active"');
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $user_fullname);
        ?>
        <?php
        while (mysqli_stmt_fetch($stmt)) {
          echo "<option value='$user_id'>$user_fullname</option>";
        }
        mysqli_stmt_close($stmt);
        ?>
      </select>

      <input type="submit" value="Add project" name="submitproject" class="text-white">
      <?php
      if (!empty($_GET['projectname']) && !empty($_GET['submitproject']) && !empty($_GET['managers'])) {
        $name = $_GET['projectname'];
        $sub = $_GET['submitproject'];
        $projectdesc= $_GET['projectdesc'];
        $managerid = $_GET['managers'];
        $projectownerid = $_SESSION['id'];

        $sql = 'INSERT INTO projects  (project_name, project_description ,scrum_master_id,productowner_id ) VALUES (?, ?, ?,?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $name, $projectdesc,$managerid,$projectownerid );

        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
          $prjid = mysqli_insert_id($conn);

          $updatesql = "UPDATE users SET project_id = ?, user_role = 'scrum master', user_status = 'active' WHERE user_id = ?";
          $updatestmt = mysqli_prepare($conn, $updatesql);
          mysqli_stmt_bind_param($updatestmt, "ii", $prjid, $managerid);
          $updateresult = mysqli_stmt_execute($updatestmt);
          $sql2=" UPDATE users set user_status='active' , project_id = $prjid WHERE user_id = $projectownerid ";
          mysqli_query($conn , $sql2);
          
          $_SESSION['projectmanagerid'] = $managerid;
          if ($updateresult) {
            echo 'Project added successfully, and user status updated.';
          }

          mysqli_stmt_close($updatestmt);
        } else {
          echo 'Failed to add a new project.';
        }

        mysqli_stmt_close($stmt);
      }
      ?>


    </form>
  </section>
  <h1 class="text-xl mt-10 ml-5">Welcome Back</h1>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
      <div class="flex-shrink-0">
        <img class="h-10 w-10 rounded-full" src="employee.png" alt="">
      </div>
      <div class="flex-1 min-w-0">
        <a href="#" class="focus:outline-none">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <p class="text-sm font-medium text-gray-900"><?php echo $_SESSION['fullname']; ?></p>
          <p class="text-sm text-gray-500 truncate">Product Owner</p>
        </a>
      </div>
    </div>
  </div>
  <br>

  <div class="text-center mb-10">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
    </svg>
    <div class="mt-6">
      <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="crteam2">
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Project
      </button>
    </div>
  </div>


  <div class="relative shadow-md sm:rounded-lg">
        <?php
        if (isset($_SESSION['id'])) {
          $productowner = $_SESSION['id'];
          $_SESSION['type'] = 'productowner';

          $sql = "SELECT projects.project_id, projects.project_name,projects.project_status,projects.project_description , users.user_fullname
            FROM projects
            INNER JOIN users ON users.user_id = projects.scrum_master_id
            WHERE projects.productowner_id=? AND users.user_role='scrum master'";

          $stmt = mysqli_prepare($conn, $sql);

          if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $productowner);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $project_id, $project_name, $project_status,$projectdesc, $user_fullname);
            echo "<section class='h-[100vh] flex flex-wrap'>";
            while (mysqli_stmt_fetch($stmt)) {
              echo "
                <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'>
                <ul role='list' class='flex flex-col items-center'>
                    <li class='col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200'>
                        <div class='flex-1 flex flex-col p-8'>
                           <h2 class='mt-6 text-gray-900 text-sm font-medium'>project name</h2>
                           <dd class='text-gray-500 text-sm'>$project_name</dd>
                            <dl class='mt-1 flex-grow flex flex-col justify-between'>
                            <h2 class='mt-6 text-gray-900 text-sm font-medium'>project status</h2>
                                <dd class='text-gray-500 text-sm'>$project_status</dd>
                                <h2 class='mt-6 text-gray-900 text-sm font-medium'>project description</h2>
                                <dd class='mt-3'>
                                    <span class='px-2 py-1 text-green-800 text-xs font-medium bg-green-100 rounded-full'>$projectdesc</span>
                                </dd>
                                <h2 class='mt-6 text-gray-900 text-sm font-medium'>project Manager</h2>
                                <dd class='text-gray-500 text-sm'>$user_fullname</dd>
                            </dl>
                        </div>
                        <div class='-mt-px flex flex-col'>
                            <div class='w-[100%] flex-1 flex justify-center bg-green-200'>
                            <a href='assign.php?idprj=$project_id'>edit</a>
                            </div>
                            <div class='-ml-px w-[100%] flex-1 flex justify-center bg-red-200'>
                            <a href='delete.php?idprj=$project_id'>Delete</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>";
            }
            echo "</section>";
            $_SESSION['id']= $productowner;
            mysqli_stmt_close($stmt);
          }
        }
        ?>

      
  </div>



  <script type="text/javascript" src="productowner.js"></script>
</body>

</html>
