<?php
include("databaseconn.php");
session_start();
// if(isset($_SESSION['id'])){
//   header('location:login.php');
// }
 $unable=false;
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
  <header class="w-[100%] bg-gray-900 py-5 text-white flex flex-row ">
    <a href="index.php" class="ml-5">Go back</a>
  </header>


  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <form class="space-y-6" action="login.php" method="POST">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
          <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Log in</h2>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
          <div class="mt-1">
            <input id="emaillog" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <span class="text-red-500 hidden" id="regnamex"></span>
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700"> Password </label>
          <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          </div>
        </div>

        <div>
          <input type="submit" value="Log In" name="login" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <?php if($unable){echo '<h3>Unable to log in</h3>';} ?>
        </div>
      </form>

      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
  </div>
  <script src="mt.js"></script>
</body>

</html>


<?php
if (isset($_POST['login'])) {
 
  $email = $_POST['email'];
  $password = $_POST['password'];
  $validemail = '/^(([a-zA-Z]{1,})\d{1,}@[a-z]{1,}\.[a-z]{1,3}|[a-z]+@[a-z]+\.[a-z]{1,3})$/';
  $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
  $filteredPass = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
  if (preg_match($validemail, $filteredEmail)) {
    $pstm = mysqli_prepare($conn, " SELECT user_id, user_fullname ,user_email, user_password,user_role FROM users WHERE user_email = ? ");
    mysqli_stmt_bind_param($pstm, "s", $filteredEmail);
    mysqli_stmt_execute($pstm);
    mysqli_stmt_bind_result($pstm, $user_id,$user_fullname ,$user_email, $user_password,$user_role);
    mysqli_stmt_fetch($pstm);
    mysqli_stmt_close($pstm);
    if ($user_email === $filteredEmail && password_verify($filteredPass, $user_password)) {
      $_SESSION['id'] = $user_id;
      $_SESSION['email'] = $user_email;
      $_SESSION['role'] = $user_role;
      $_SESSION['fullname'] = $user_fullname;
      if($_SESSION['role']==='member'){
       header('location:memberdash.php');   
      }elseif($_SESSION['role']==='admin'){
        header('location:adm.php');
      }elseif($_SESSION['role']==='scrum master'){
        header('location:scrum.php');
      }elseif($_SESSION['role']==='Product Owner'){
        header('location:productowner.php');
      }
    } else {
      $unable = true;
    }
  }
}
?>