<?php
include("databaseconn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Document</title>
</head>

<body>
  <header class="w-[100%] bg-gray-900 py-5 text-white flex flex-row justify-around">
    <h2 class="ml-5 w-2/5">DataWare</h2>
    <a href="login.php">log in</a>
  </header>



  <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign up</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" action="index.php" method="POST">

          <div>
            <label for="fullname" class="block text-sm font-medium text-gray-700"> Full Name </label>
            <div class="mt-1">
              <input id="fullname" name="fullname" type="text" autocomplete="fullname" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <span class="text-red-500 hidden" id="regname"></span>
            </div>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
            <div class="mt-1">
              <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <span class="text-red-500 hidden" id="regemail"></span>
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700"> Password </label>
            <div class="mt-1">
              <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700"> confirm your Password </label>
            <div class="mt-1">
              <input id="rpassword" name="rpassword" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ">
            </div>
          </div>
          <div>
            <input type="submit" name="signup" value="Sign up" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <?php
            if (isset($_POST['fullname'])) {
              $fullname = $_POST['fullname'];
            }
            if (isset($_POST['email'])) {
              $email = $_POST['email'];
            }
            if (isset($_POST['password'])) {
              $password = $_POST['password'];
            }
            $validname = '/^[a-zA-Z]{3,}\s[a-zA-Z]{3,}$/';
            $validemail = '/^(([a-zA-Z]{1,})\d{1,}@[a-z]{1,}\.[a-z]{1,3}|[a-z]+@[a-z]+\.[a-z]{1,3})$/';

            if (isset($_POST['signup'])) {
              $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
              $filteredName = filter_var($fullname, FILTER_SANITIZE_SPECIAL_CHARS);
              $filteredPass = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
              $hashedpass = password_hash($filteredPass, PASSWORD_BCRYPT);
              if (preg_match($validemail, $filteredEmail) && preg_match($validname, $filteredName)) {
                $pstm = mysqli_prepare($conn, "INSERT INTO users(user_fullname,user_email,user_password) VALUES(?,?,?)");
                mysqli_stmt_bind_param($pstm, "sss", $filteredName, $filteredEmail, $hashedpass);
                $result = mysqli_stmt_execute($pstm);
                if ($result) {
                  echo '<h3 class="text-green-500	">You have Registerd successfully</h3>';
                }
                mysqli_stmt_close($pstm);
              } else {
                echo '<h3 class="text-red-600">failed to register!!</h3>';
              }
            }

            ?>
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





  <script type="text/javascript" src="mt.js"></script>
</body>

</html>