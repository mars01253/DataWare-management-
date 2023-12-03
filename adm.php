<?php
session_start();
include("databaseconn.php");
error_reporting(0);
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
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center w-[100%] justify-between">
          <div class="w-[10%]">
            <h1 class="text-white">DataWare</h1>
          </div>
          <div class="flex flex-col sm:block sm:ml-6">
            <div class="flex space-x-4 ml-50 ">
              <form action="adm.php" method="post" class="flex w-[100%]">
                <input type="submit" value="Members" name="members" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
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
    </div>
  </nav>

  <h1 class="text-xl mt-10 ml-5">Welcome Back</h1>
  <div class="w-[100%]">
    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
      <div class="flex-shrink-0">
        <img class="h-10 w-10 rounded-full" src="eo.jpg" alt="..">
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
      <?php
            if (isset($_POST['members']) ) {
              $choice = 'members';
            }if(isset($_POST['stats'])){
              $choice = 'stats';
            } ?>
  
        <?php
        if($choice === 'members'){
        $sql = 'SELECT user_id, user_fullname, user_role, user_status FROM users WHERE user_role!="admin"';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id,$user_fullname, $user_role,$status);
        $_SESSION['type']='adm';
          echo "<section class='h-[100vh] flex flex-wrap'>";
        while (mysqli_stmt_fetch($stmt)) {
          echo "
          <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'>
              <ul role='list' class='flex flex-col items-center'>
                  <li class='col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200'>
                      <div class='flex-1 flex flex-col p-8'>
                          <h3 class='mt-6 text-gray-900 text-sm font-medium'>$user_fullname</h3>
                          <dl class='mt-1 flex-grow flex flex-col justify-between'>
                              <dd class='text-gray-500 text-sm'>$status</dd>
                              <dd class='mt-3'>
                                  <span class='px-2 py-1 text-green-800 text-xs font-medium bg-green-100 rounded-full'>$user_role</span>
                              </dd>
                          </dl>
                      </div>
                      <div class='-mt-px flex flex-col'>
                          <div class='w-[100%] flex-1 flex items-center bg-green-200'>
                              <a href='assign.php?id=$user_id'>Assign as Product owner</a>
                          </div>
                          <div class='-ml-px w-[100%] flex-1 flex justify-center bg-red-200'>
                              <a href='delete.php?id=$user_id'>Delete</a>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>";
      }
      echo "</section>";

        mysqli_stmt_close($stmt);
      }


      ?>
      <?php
     
      if ($choice === 'stats') {
        $sql = "SELECT COUNT(*) FROM users";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $sql2 = "SELECT COUNT(*) FROM equipe";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $result2); 
        mysqli_stmt_fetch($stmt2);
        mysqli_stmt_close($stmt2);
        $sql3 = "SELECT COUNT(*) FROM projects";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_bind_result($stmt3, $result3);
        mysqli_stmt_fetch($stmt3);
        mysqli_stmt_close($stmt3);
        echo "<div>
                <h3 class='text-lg leading-6 font-medium text-gray-900'>Statistics</h3>
                <dl class='mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3'>
                    <div class='px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6'>
                        <dt class='text-sm font-medium text-gray-500 truncate'>Total Members</dt>
                        <dd class='mt-1 text-3xl font-semibold text-gray-900'>$result</dd>
                    </div>
    
                    <div class='px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6'>
                        <dt class='text-sm font-medium text-gray-500 truncate'>Total Teams</dt>
                        <dd class='mt-1 text-3xl font-semibold text-gray-900'>$result2</dd>
                    </div>
    
                    <div class='px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6'>
                        <dt class='text-sm font-medium text-gray-500 truncate'>Total Projects</dt>
                        <dd class='mt-1 text-3xl font-semibold text-gray-900'>$result3</dd>
                    </div>
                </dl>
            </div>";
    }
?>    
  




</body>

</html>