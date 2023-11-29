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
            <img class="block lg:hidden h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
            <img class="hidden lg:block h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow">
          </div>
          <div class="hidden sm:block sm:ml-6 w-1/">
            <div class="flex space-x-4 ml-50 ">
              <form action="scrum.php" method="post" class="flex items-center w-[100%]">
                <input type="submit" value="My Teams" name="projects" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                <input type="submit" value="Members" name="members" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
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
        <input type="submit" value="My Projects" name="projects" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium">
        <input type="submit" value="My Teams" name="teams" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
        <input type="submit" value="Members" name="members" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
        <input type="submit" value="Stats" name="stats" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
        <a href="logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log out</a>
      </div>
    </div>
    </div>
  </nav>


  <section class="bg-blue-50 w-[100vw] h-[90vh] absolute z-9 flex flex-col items-center justify-center hidden" id="teampopup">
    <form action="scrum.php" method="post" class="bg-[#1f2937] relative w-[50%] px-15 py-20 z-10 rounded-xl flex flex-col items-center gap-5">
      <img src="img/close.svg" alt="..." class="w-[6%] float-right mr-3 relative top-3 right-20 closeform">
      <input type="text" placeholder="Team Name" name="teamname" class="w-[60%] rounded-xl">
      <select name="project_id" id="projects" class="w-[60%] rounded-xl">
        <option value="" disabled selected hidden>Choose your project:</option>
        <?php
        $scrmid = $_SESSION['id'];
        $stmt = mysqli_prepare($conn, "SELECT project_id , project_name FROM projects WHERE scrum_master_id = ? AND equipe_id IS NULL");
        mysqli_stmt_bind_param($stmt, "i", $scrmid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $project_id, $project_name);

        while (mysqli_stmt_fetch($stmt)) {
          echo "<option value='$project_id'>$project_name</option>";
        }

        mysqli_stmt_close($stmt);
        ?>
      </select>
      <input type="submit" value="Add Team" name="submitteam" class="text-white">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitteam'])) {
      $name = $_POST['teamname'];
      $selected_project_id = $_POST['project_id'];

      if (!empty($name) && !empty($selected_project_id)) {
        $sql = 'INSERT INTO equipe (equipe_name, project_id, scrum_master_id, equipe_status) VALUES (?, ?, ?, "active")';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $name, $selected_project_id, $scrmid);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
          $lastInsertedId = mysqli_insert_id($conn);
          mysqli_begin_transaction($conn);

          $sql2 = "UPDATE projects SET equipe_id = ? WHERE scrum_master_id = ?";
          $stmt2 = mysqli_prepare($conn, $sql2);
          mysqli_stmt_bind_param($stmt2, "ii", $lastInsertedId, $scrmid);
          $result2 = mysqli_stmt_execute($stmt2);

          $sql3 = "UPDATE users SET equipe_id = ?, user_status = 'active', user_role = 'scrum master', project_id = ? WHERE user_id = ?";
          $stmt3 = mysqli_prepare($conn, $sql3);
          mysqli_stmt_bind_param($stmt3, "iii", $lastInsertedId, $selected_project_id, $scrmid);
          $result3 = mysqli_stmt_execute($stmt3);
          if ($result2 && $result3) {
            mysqli_commit($conn);
            echo 'Team added successfully';
          } else {
            mysqli_rollback($conn);
            echo 'Failed to update projects or users table';
          }

          mysqli_stmt_close($stmt2);
          mysqli_stmt_close($stmt3);
        } else {
          echo 'Failed to add a new team';
        }

        mysqli_stmt_close($stmt);
      } else {
        echo 'Team name and project must be specified';
      }
    }
    ?>

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
          <p class="text-sm text-gray-500 truncate"><?php echo $_SESSION['role']; ?></p>
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
      <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="crteam">
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Team
      </button>
    </div>
  </div>



  <div class="relative  shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-4 py-3">
            <?php $choice = 'project';
            if (isset($_POST['projects'])) {
              $choice = 'project';
              echo " teams";
            } elseif (isset($_POST['members'])) {
              $choice = 'member';
              echo "Members";
            } ?>
          </th>
          <th scope="col" class="px-4 py-3">
            <?php if (isset($_POST['projects'])) {
              echo "team status";
            } elseif (isset($_POST['members'])) {
              echo "Member Status";
            } ?>
          </th>
          <th scope="col" class="px-4 py-3">
            <?php if (isset($_POST['projects'])) {
              echo "Project Name";
            } elseif (isset($_POST['members'])) {
              echo "Member Team";
            } ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($choice === 'project') {
          $sql = "SELECT equipe.equipe_id, equipe_name, equipe_status, equipe.project_id, projects.project_name
        FROM equipe
        INNER JOIN projects ON equipe.project_id = projects.project_id 
        WHERE equipe.scrum_master_id = ?";
          $stmt = mysqli_prepare($conn, $sql);

          if ($stmt) {
            $_SESSION['type'] = 'scrummaster';
            mysqli_stmt_bind_param($stmt, "i", $scrmid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $equipe_id, $equipe_name, $equipe_status, $project_id, $project_name);

            while (mysqli_stmt_fetch($stmt)) {
              echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>
                <td class='px-6 py-4'>$equipe_name</td>
                <td class='px-4 py-4'>$equipe_status</td>
                <td class='px-4 py-4'>$project_name</td>
                <td class='px-4 py-4'>
                    <a href='assign.php?id=$equipe_id'>edit</a>
                </td>
                <td class='px-2 py-4'>
                    <a href='delete.php?id=$equipe_id'>Delete</a>
                </td>
              </tr>";
            }


            $_SESSION['id'] = $scrmid;
            $_SESSION['idequipe'] = $equipe_id;
            mysqli_stmt_close($stmt);
          } else {
            echo "Error in the SQL query: " . mysqli_error($conn);
          }
        }
        if ($choice === 'member') {
          $sql = "SELECT users.user_id, users.user_fullname, users.user_status, equipe.equipe_name
                  FROM users
                  LEFT JOIN equipe ON users.equipe_id = equipe.equipe_id
                  WHERE users.user_role='member'";
          $stmt = mysqli_prepare($conn, $sql);

          if ($stmt) {
            $_SESSION['id'] = $scrmid;
            $_SESSION['type'] = 'member';
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $user_id, $user_fullname, $status, $equipe_name);

            while (mysqli_stmt_fetch($stmt)) {
              echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>
                                <td class='px-6 py-4'>$user_fullname</td>
                                <td class='px-4 py-4'>$status</td>
                                <td class='px-4 py-4'>$equipe_name</td>
                                <td class='px-4 py-4'><a href='assign.php?iduser=$user_id'>Assign A team</a></td>
                                <td class='px-4 py-4'><a href='delete.php?iduser=$user_id'>Remove From Teams</a></td>
                            </tr>";
            }
            mysqli_stmt_close($stmt);
          }
        }
        ?>


      </tbody>
    </table>
  </div>



  <script type="text/javascript" src="scrum.js"></script>
</body>

</html>