<?php
// Include config.php and start session
@include 'config.php';
session_start();

// Redirect to login page if admin is not logged in
if(!isset($_SESSION['admin_name'])){
    header('location:login_form.php');
    exit; // Stop further execution
}

// Initialize an empty array to store students data
$students= [];

// Check if date filter is set in GET request
$dateFilter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

// Prepare SQL query with optional date filter
$query = "SELECT sessions.*, student.*, sessions.purpose AS session_purpose 
          FROM sessions 
          INNER JOIN student ON student.id = sessions.student_id";

// Add date filter condition if date filter is provided
if ($dateFilter) {
    $query .= " WHERE DATE(sessions.time_out) = '$dateFilter'";
}

$query .= " AND sessions.time_out IS NOT NULL 
            ORDER BY sessions.time_out ASC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>
    <script src="https://cdn.tailwindcss.com"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/dashboard_admin.css">

</head>
<body>
<div class="content">
    <form method="GET">
        <label for="date_filter" class="text-white">Filter by Date:</label>
        <input type="date" id="date_filter" name="date_filter">
        <button type="submit" class="text-white"></button>
        <button id="exportBtn" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Export to Excel</button>
  </div>
    </form>
    <p class="welcome-text">Students SitIn History Records</p>
</div>
   
<header>
    <nav class="navbar">
        <a href="./" class="img">SeatFlow <span>.</span></a>         
    </nav>
</header>

<div class="container1">
 <table class="mt-5 w-full text-sm text-left rtl:text-right text-white rounded-md  ">
                <thead class="text-xs bg-[#151318] border border-white  uppercase border-b-0">
                    <tr>
                        <th class="border px-4 py-4 font-medium border-none text-center font-bold">ID NO
                        </th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">FIRST NAME</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">LAST NAME</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">SESSIONS</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">EMAIL</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">TIME IN</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">TIME OUT</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">Purpose</th>
                        <th class="border px-4 py-4 font-medium border-none  text-center">Operation</th>
                    </tr>
                </thead>
                <tbody id="tbody" class="relative">
                    

                <?php 

            foreach ($students as $student) {
                   echo '<tr class="odd:bg-[#8e523d] bg-[#b6a87c] border-r border-l border-white">
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['idnum'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['firstname'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['lastname'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['sessions'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['email'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['time_in'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['time_out'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">'.$student['session_purpose'].'</td>
                                <td class="border px-4 py-4 border-none text-center text-xs md:text-sm text-white">' . '<span href="#" class="text-white  bg-[#151318] px-3 p-2 rounded-md">Finished</span>' . '</td></tr>';
                }
            ?>

                </tbody>

            </table>
   <div class="content">
      <p class="welcome-text">Students SitIn History Records</p>
   </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>

  <script>
// Function to export table data to Excel
function exportToExcel() {
  const table = document.querySelector('table');
  
  // Convert time strings to proper time format
  const timeCells = table.querySelectorAll('td[data-time]');
  timeCells.forEach(cell => {
    const timeString = cell.getAttribute('data-time');
    const timeParts = timeString.split(':');
    const hours = parseInt(timeParts[0], 10);
    const minutes = parseInt(timeParts[1], 10);
    const timeValue = new Date(0, 0, 0, hours, minutes);
    cell.textContent = timeValue.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  });
  
  const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet JS" });
  XLSX.writeFile(wb, 'table_data.xlsx');
}


    // Event listener for export button click
    document.getElementById('exportBtn').addEventListener('click', exportToExcel);
  </script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
