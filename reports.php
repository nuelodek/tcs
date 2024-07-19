<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css">
    <style>body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        background-color: white;
    }
  
    .container {
        display: flex;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    /* .sidebar {
        width: 200px;
        background-color: #fff;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    } */
    
    .sidebar ul {
        list-style: none;
        padding: 0;
    }
    
    .sidebar ul li {
        margin-bottom: 10px;

    }
    
    .sidebar ul li a{
        text-decoration: none;
        color: #333;
        font-weight: bold;
        
        font-size: 11px;
    }

    .report-category ul li a{
        text-decoration: none;
        color: #333;
        
    }
    
    .content {
        flex: 1;
        padding: 20px;
    }
    

    
    .request-report-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 4px;
    }
    
    .reports-section {
    }
    
    .report-category {
        margin-bottom: 20px;
    }
    
    .report-category h3 {
        margin-bottom: 10px;
    }
    
    .report-category ul {
        list-style: none;
        padding: 0;
    }
    
    .report-category ul li {
        margin-bottom: 5px;
    }
    </style>
</head>
<body>
   
    <div class="container">
       
        <main class="content">
            <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="repor">  
            <h1>Reports</h1>
                <p>Choose a report from our library of templates or request a specific report</p>
                </div>
                <div class="request-report">
                <button class="request-report-btn">Request a Report</button>
                </div>
            </div>
           
        
                <div class="reports-section" style="display: flex; gap: 20px;">
                    <div class="report-category" style="margin-top: 5px; width: 150px;">
                            <aside class="sidebar">
                                <ul>
                                    <li><a href="#">GDPR Logs</a></li>
                                    <li><a href="#">Policies</a></li>
                                    <li><a href="#">Programme</a></li>
                                    <li><a href="#">Risk Register</a></li>
                                    <li><a href="#">Users</a></li>
                                    <li><a href="#">Training</a></li>
                                </ul>
                            
                                </aside>
                    </div>
                      <div class="report-wrapper" class="gdpr-logs">
                          <h2>GDPR Logs</h2>
                          <div class="report-category">
                              <h3>Data Processing Activities</h3>
                              <ul>
                                  <li><a href="data_processing_activities.php" target="_blank">Data Processing Activities by Categories of Data Subject</a></li>
                                  <li><a href="data_controller_log.php" target="_blank">Data Controller Log</a></li>
                                  <li><a href="data_processor_log.php" target="_blank">Data Processor Log</a></li>
                                  <li><a href="data_processing_risks.php" target="_blank">Data Processing Activities - Suggested vs Created Risks</a></li>
                              </ul>
                          </div>
                          <div class="report-category">
                              <h3>Data Subject Log</h3>
                              <ul>
                                  <li><a href="data_subject_requests_ytd.php" target="_blank">Data Subject Requests Received - Year to Date</a></li>
                                  <li><a href="data_subject_requests_comparison.php" target="_blank">Data Subject Requests Received - This Year vs Last Year</a></li>
                                  <li><a href="data_subject_requests_resolved_ytd.php" target="_blank">Data Subject Requests (Resolved) - Year to Date</a></li>
                                
                              </ul>
                          </div>

                          <div class="report-category">
                              <h3>Data Breach Log</h3>
                              <ul>
                                  <li><a href="data_breaches.php" target="_blank">Data Breaches / Incidents Discovered Between Dates</a></li>                            </ul>
                          </div>
                          <div class="report-category">
                              <h3>Data Retention Log</h3>
                              <ul> 
     
                              <li><a href="data_retention_ytd.php" target="_blank">Data Retention Received - Year To Date</a></li>
                              <li><a href="data_retention_comparison.php" target="_blank">Data Retention Requests Received - This Year vs Last Year</a></li>                            </ul>
                          </div>               
                         </div>
                    </div>
                        
</div>



                    
                  </main>
                </div>
    </div>
</body>
</html>
