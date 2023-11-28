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
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center w-[100%] justify-between">
          <div class="flex-shrink-0">
            <img class="block lg:hidden h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
            <img class="hidden lg:block h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg" alt="Workflow">
          </div>
          <div class="hidden sm:block sm:ml-6">
            <div class="flex space-x-4 ml-50 ">
              <form action="memberdash.php" method="post" class="flex w-[100%]">
                <input type="submit" value="My Teams" name="projects" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                <input type="submit" value="My Projects" name="teams" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
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
        <input type="submit" value="Stats" name="stats" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
        <input type="submit" value="Log Out" name="logout" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
      </div>
    </div>
    </div>
  </nav>

  <h1 class="text-xl mt-10 ml-5">Welcome Back</h1>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
      <div class="flex-shrink-0">
        <img class="h-10 w-10 rounded-full" src="" alt="">
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


  <div class="relative  shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-4 py-3">
            <?php if (isset($_POST['projects'])) {
              echo "my teams";
            } elseif (isset($_POST['teams'])) {
              echo "my Projects";
            } ?>
          </th>
          <th scope="col" class="px-4 py-3">
            <?php if (isset($_POST['projects'])) {
              echo "team status";
            } elseif (isset($_POST['teams'])) {
              echo "Project status";
            } ?>
          </th>
          <th scope="col" class="px-4 py-3">
            <?php if (isset($_POST['projects'])) {
              echo "Project Name";
            } elseif (isset($_POST['teams'])) {
              echo "Team Name";
            } ?>
          </th>
          <th scope="col" class="px-4 py-3">
            Scrum Master
          </th>
        </tr>

      </thead>
      <tbody>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

          </th>
          <td class="px-4 py-4">

          </td>
          <td class="px-4 py-4">

          </td>
          <td class="px-4 py-4">

          </td>
        </tr>
        </tr>
      </tbody>
    </table>
  </div>

  <script src="mt.js"></script>
</body>

</html>
<?php



?>