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

    .modal {
        height: 70vh;
        overflow: auto;
    }
.modal label {
 margin-bottom: 10px;
 margin-top: 10px;
}

.modal input {
    width: 90%;
    border-radius: 3px;
    padding: 10px;
    border: 1px solid grey;
    outline: none;
}

.modal textarea {
    width: 90%;
    border-radius: 3px;
    padding: 20px;
    border: 1px solid grey;
    outline: none;
}
form {
    display: flex;
    flex-direction: column;
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
                            <button class="request-report-btn" onclick="openModal()">Request a Report</button>                          
                </div>
            </div>
           
        
                <div class="reports-section" style="display: flex; gap: 20px;">
                    <div class="report-category" style="margin-top: 5px; width: 150px;">
                            <aside class="sidebar">
                                <ul>
                                    <li><a href="#" onclick="togglegdpr()">GDPR Logs</a></li>
                                    <li><a href="#" onclick="togglePolicies()">Policies</a></li>
                                    <li><a href="#" onclick="toggleProgramme()">Programme</a></li>
                                    <li><a href="#" onclick="toggleRiskRegister()">Risk Register</a></li>
                                    <li><a href="#" onclick="toggleUsers()">Users</a></li>
                                    <li><a href="#" onclick="toggleTraining()">Training</a></li>
                                </ul>
                                </aside>
                    </div>

                
                      <div class="report-wrapper gdpr"> 
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


                    <div class="report-wrapper policies" style="display:none;">
                        <h2>Policies</h2>
                        <div class="report-category">
                            <!-- <h3>Policy Reports</h3> -->
                            <ul>
                                <li><a href="document_summary_report.php" target="_blank">Document Summary Report</a></li>
                                <li><a href="third_party_agreements_and_documents.php" target="_blank">Third Party Agreements & Documents</a></li>
                                <li><a href="documents_due_for_review_report.php" target="_blank">Documents Due For Review Report</a></li>
                            </ul>
                        </div>
                    </div>

<div class="report-wrapper programme" style="display: none;">
    <h2>Programme</h2>
    <div class="report-category">
        <!-- <h3>Programme Reports</h3> -->
        <ul>
            <li><a href="summary_report.php" target="_blank">Summary Report</a></li>
            <li><a href="programme_of_work_tasks_all.php" target="_blank">Programme of Work - Tasks (all)</a></li>
            <li><a href="programme_of_work_tasks_filtered.php" target="_blank">Programme of Work - Tasks (filtered)</a></li>
            <li><a href="tasks_completed_between_dates.php" target="_blank">Tasks completed between dates</a></li>
            <li><a href="tasks_overdue.php" target="_blank">Tasks overdue</a></li>
            <li><a href="tasks_due_for_completion_between_dates.php" target="_blank">Tasks due for completion between Due Dates</a></li>
        </ul>
    </div>
</div>

<div class="report-wrapper risk-register" style="display: none;">
    <h2>Risk Register</h2>
    <div class="report-category">
        <!-- <h3>Risk Reports</h3> -->
        <ul>
            <li><a href="risk_register.php" target="_blank">Risk Register</a></li>
            <li><a href="risk_actions_completed_between_dates.php" target="_blank">Risk Actions completed between Dates</a></li>
            <li><a href="risk_actions_by_data_champion_and_completed_dates.php" target="_blank">Risk Actions by Data Champion and Completed Dates</a></li>
            <li><a href="risks_by_keyword.php" target="_blank">Risks by keyword</a></li>
            <li><a href="risk_profile_summary_report.php" target="_blank">Risk Profile Summary Report</a></li>
            <li><a href="risk_profile_detail_report.php" target="_blank">Risk Profile Detail Report</a></li>
            <li><a href="risk_actions_not_completed_between_dates.php" target="_blank">Risk Actions not completed between dates</a></li>
            <li><a href="risks_and_actions_report.php" target="_blank">Risks and Actions Report</a></li>
        </ul>
    </div>
</div>

<div class="report-wrapper users" style="display: none;">
    <h2>Users</h2>
    <div class="report-category">
        <!-- <h3>User Reports</h3> -->
        <ul>
            <li><a href="users_report.php" target="_blank">Users Report</a></li>
            <li><a href="inactive_users_report.php" target="_blank">InActive Users Report</a></li>
            <li><a href="user_departments_report.php" target="_blank">User Departments Report</a></li>
        </ul>
    </div>
</div>

<div class="report-wrapper training" style="display: none;">
    <h2>Training</h2>
    <div class="report-category">
        <!-- <h3>Training Reports</h3> -->
        <ul>
            <li><a href="training_course_summary.php" target="_blank">Training Course Summary Report</a></li>
            <li><a href="phishing_course_report.php" target="_blank">Phishing Course Report</a></li>
            <li><a href="phishing_report_by_department.php" target="_blank">Phishing Report by Department</a></li>
            <li><a href="training_audit_uninvited_users.php" target="_blank">Training Audit (Uninvited Users)</a></li>
            <li><a href="training_stats_per_department.php" target="_blank">Training Stats per Department</a></li>
        </ul>
    </div>
</div>
                    </div>
                        




    <script>
    function togglegdpr() {
      document.querySelector(".gdpr").style.display = "block";
      document.querySelector(".policies").style.display = "none";
      document.querySelector(".programme").style.display = "none";
      document.querySelector(".risk-register").style.display = "none";
      document.querySelector(".users").style.display = "none";
      document.querySelector(".training").style.display = "none";
    }

    function togglePolicies() {
      document.querySelector(".gdpr").style.display = "none";
      document.querySelector(".policies").style.display = "block";
      document.querySelector(".programme").style.display = "none";
      document.querySelector(".risk-register").style.display = "none";
      document.querySelector(".users").style.display = "none";
      document.querySelector(".training").style.display = "none";
    }

    function toggleProgramme() {
      document.querySelector(".gdpr").style.display = "none";
      document.querySelector(".policies").style.display = "none";
      document.querySelector(".programme").style.display = "block";
      document.querySelector(".risk-register").style.display = "none";
      document.querySelector(".users").style.display = "none";
      document.querySelector(".training").style.display = "none";
    }

    function toggleRiskRegister() {
      document.querySelector(".gdpr").style.display = "none";
      document.querySelector(".policies").style.display = "none";
      document.querySelector(".programme").style.display = "none";
      document.querySelector(".risk-register").style.display = "block";
      document.querySelector(".users").style.display = "none";
      document.querySelector(".training").style.display = "none";
    }

    function toggleUsers() {
      document.querySelector(".gdpr").style.display = "none";
      document.querySelector(".policies").style.display = "none";
      document.querySelector(".programme").style.display = "none";
      document.querySelector(".risk-register").style.display = "none";
      document.querySelector(".users").style.display = "block";
      document.querySelector(".training").style.display = "none";
    }

    function toggleTraining() {
      document.querySelector(".gdpr").style.display = "none";
      document.querySelector(".policies").style.display = "none";
      document.querySelector(".programme").style.display = "none";
      document.querySelector(".risk-register").style.display = "none";
      document.querySelector(".users").style.display = "none";
      document.querySelector(".training").style.display = "block";
    }
    </script>


  
  </script>

                    
                  </main>
                </div>
    </div>
    <script>
                                function openModal() {
                                    document.getElementById("reportModal").style.display = "block";
                                }

                                function closeModal() {
                                    document.getElementById("reportModal").style.display = "none";
                                }

                                // Close the modal if clicked outside of it
                                window.onclick = function(event) {
                                    if (event.target == document.getElementById("reportModal")) {
                                        closeModal();
                                    }
                                }

                                // Handle form submission
                                document.getElementById("reportRequestForm").onsubmit = function(event) {
                                    event.preventDefault();
                                    // Add your form submission logic here
                                    alert("Report request submitted!");
                                    closeModal();
                                }
                            </script> 

    

      <div id="reportModal" class="modal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; background-color: white; max-width:500px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px;">
                                    <div class="modal-content" style="display: flex; flex-direction: column; padding: 20px;">
                                        <span class="close" onclick="closeModal()" style="align-self: flex-end; cursor: pointer; font-size: 24px;">×</span>
                                        <h2>Request a Report</h2>
                                        <form>
                                            <label for="feature">Which Yenetai feature are you looking to utilise data from?*</label>
                                            <input type="text" id="feature" name="feature" required>
                                          
                                            <label for="objective">What is the objective or purpose of the report you need?*</label>
                                            <textarea id="objective" name="objective" required></textarea>
                                            <small>Example: I want to utilise data from the Risk Register feature</small>
                                          
                                            <label for="columns">What columns would you like to appear in the report?*</label>
                                            <textarea id="columns" name="columns" required></textarea>
                                            <small>Example: "Risk Title, Risk Description, Risk Impact, Risk Likelihood"</small>
                                          
                                            <label for="parameters">What parameters would you like to use to filter the data?*</label>
                                            <textarea id="parameters" name="parameters" required></textarea>
                                            <small>Example: "I want to apply filters to segment the risks by departments, projects, or data processing activities."</small>
                                          
                                            <label for="report-name">Provide a Report Name:*</label>
                                            <input type="text" id="report-name" name="report-name" required>
                                          
                                            <label for="report-sample">Attach a report sample*</label>
                                            <input type="file" id="report-sample" name="report-sample" required>
                                          
                                            <label for="dateRange">Date Range:</label>
                                            <input type="text" id="dateRange" name="dateRange" required style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">

                                            <label for="additionalInfo">Additional Information:</label>
                                            <textarea id="additionalInfo" name="additionalInfo" rows="4" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;"></textarea>

                                            <button type="submit" style="padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">Submit Request</button>
                                        </form>
                                    </div>
                                </div>

</body>
</html>