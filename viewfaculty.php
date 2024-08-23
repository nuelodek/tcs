  <?php
  // Include necessary files and start session
  include "db.php";

  // Get the school ID of the logged-in admin
  $aid = htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8'); // this is adminid
  $ret = 'SELECT school_id FROM users WHERE id=?';
  $stmt = $conn->prepare($ret);
  $stmt->bind_param('i', $aid);
  $stmt->execute();
  $stmt->bind_result($school_id);
  $stmt->fetch();
  $stmt->close();

  // Get all faculty members (professors and coordinators) from the same school
  $query = "SELECT u.id, u.first_Name, u.role_id, 
            GROUP_CONCAT(DISTINCT cat.Name SEPARATOR ', ') as categories,
            GROUP_CONCAT(DISTINCT c.Name SEPARATOR ', ') as courses,
            CASE WHEN r.Id IS NOT NULL THEN 1 ELSE 0 END as has_request
            FROM users u
            LEFT JOIN categories cat ON u.id = cat.facultyid AND cat.status = 'approved'
            LEFT JOIN courses c ON cat.Id = c.Category_Id
            LEFT JOIN requests r ON (u.id = r.Applicant_Id OR u.id = r.Coordinator_Id)
            WHERE u.school_id = ? AND (u.role_id = 1 OR u.role_id = 2)
            GROUP BY u.id
            ORDER BY u.role_id, u.first_Name";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i', $school_id);
  $stmt->execute();
  $result = $stmt->get_result();
  ?>

  <!doctype html>
  <html lang="en" class="no-js">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="theme-color" content="#3e454c">
      <title>View Faculty</title>
     
  </head>
  <body>
      
      <div class="ts-main-content">
          <div class="content-wrapper">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-12">
                          <h2 class="page-title">View Faculty</h2>
                          <div class="panel panel-default">
                              <div class="panel-heading">Faculty List</div>
                              <div class="panel-body">
                                  <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                      <thead>
                                          <tr>
                                              <th width="5%">ID</th>
                                              <th width="20%">Name</th>
                                              <th width="10%">Role</th>
                                              <th width="25%">Categories</th>
                                              <th width="25%">Courses</th>
                                              <th width="15%">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                                      while($row = $result->fetch_assoc()) {
                                          echo "<tr>";
                                          echo "<td>".$row['id']."</td>";
                                          echo "<td>".$row['first_Name']."</td>";
                                          echo "<td>".($row['role_id'] == 1 ? "Professor" : "Coordinator")."</td>";
                                          echo "<td>".$row['categories']."</td>";
                                          echo "<td>".$row['courses']."</td>";
                                          echo "<td>
                                                  <a href='edit-faculty.php?id=".$row['id']."' title='Edit'><i class='fa fa-edit'></i></a>  
                                                  <a href='copy-faculty.php?id=".$row['id']."' title='Copy'><i class='fa fa-copy'></i></a>  
                                                  <a href='delete-faculty.php?id=".$row['id']."' title='Delete' onclick='return confirm(\"Do you want to delete?\");'><i class='fa fa-close'></i></a>";
                                          if ($row['has_request'] == 1) {
                                              echo " <span title='Has pending request'><i class='fa fa-exclamation-circle'></i></span>";
                                          }
                                          echo "</td>";
                                          echo "</tr>";
                                      }
                                      ?>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </body>
  </html>
