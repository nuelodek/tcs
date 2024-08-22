<?php

session_start();

// If the user is logged in, echo session variables
$userID = $_SESSION["user_id"];
$userName =  $_SESSION["username"];
$userEmail = $_SESSION["user_email"];
$profilecompleted = $_SESSION["profile_completed"]; 

// Add a logout link

?>

<!DOCTYPE html>
<html>
<head>
    
    
    <meta charset="UTF-8" />
    <script src="https://js.stripe.com/v3/"></script>
    <script>
    const stripe = Stripe('pk_live_51MsE7oDHi2bCzDYgvbgbuG3eNdmZD8721WQp3iJH0ZF5A6swJj8PYMmgj03nFhWNpVdI2wXcxUbsMoqyMY7se8ip00apnj2uGh');
    </script>
    
<title>
        <?php
        // Check if user is logged in
        if(isset($userName)) {
            // If logged in, display the username followed by "'s Dashboard"
            echo $userName . "'s Dashboard - Sell Your Startup";
        } else {
            // If not logged in, set a default title
            echo "SYS - Marketplace to buy and sell awesome startups ";
        }
        ?>
    </title>
<script> 
        function toggleLogin() {
            document.querySelector('.loginclass').style.display = 'block';
            document.querySelector('.signupclass').style.display = 'none';
            document.querySelector('.forgetclass').style.display = 'none';
            document.querySelector('.comdetails').style.display = 'none';

        }

        function toggleSignup() {
            document.querySelector('.signupclass').style.display = 'block';
            document.querySelector('.loginclass').style.display = 'none';
            document.querySelector('.forgetclass').style.display = 'none';
            document.querySelector('.comdetails').style.display = 'none';

        }
        
        function toggleForget() {
            
            document.querySelector('.signupclass').style.display = 'none';
            document.querySelector('.loginclass').style.display = 'none';
            document.querySelector('.forgetclass').style.display = 'block';
                        document.querySelector('.comdetails').style.display = 'none';

        }
 
    </script>
    <style>
        body {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10pt;
            color: #828282;
        }

        .ayscontainer {
            background-color: rgba(253, 250, 250, 0.89);
            width: 90%;
            margin: auto;
            min-height: 50px;
            padding-bottom: 10px;
        }

        .row2 ul {
            list-style: none;
            display: flex;
            margin-top: 5px;
            gap: 10px;
            color: black;
        }

        .row2 {
            display: flex;
            flex-direction: row;
        }

        .odk {
            background-color: rgb(255, 78, 8);
            width: 100%;
            height: 25px;
            font-size: 10pt;
        }

        .byers {
            display: flex;
            gap: 6px;
        }

        .oposl li {
            margin-bottom: 10px;
        }

        .oposl a, .oposl small {
            color: #828282;
            text-decoration: none;
        }

        .oposl small {
            font-size: 9px;
        }

        .dod {
            font-size: 9.5px;
            margin-left: 3px;
        }

        .menlist {
            margin-top: 4px;
        }

        .titlelogo {
            border: 1px solid white;
            color: white;
            margin-left: -37px;
            padding: 1px;
            margin-top: -3px;
        }

        .logoname {
            font-weight: bold;
        }

        .row2 a {
            text-decoration: none;
            color: rgb(0, 0, 0);
        }

        /* New CSS for right alignment */
       
    </style>
</head>
<body>
<div class="ayscontainer">

    <div class="odk">
        <div class="row2">

            <ul class="upelege">

                <li class="titlelogo"> SYS </li>
                <li class="logoname"> SELLYOURSTARTUP </li>
 
                <?php
                 if (isset($_SESSION['user_id'])) {
                echo "<li> <a href='#'> new </a> </li>";
                echo "<li> <a href='#' onclick='toggleThreads();'> threads </a></li>";
                echo "<li> <a href='#' onclick='toggleTerms();' > terms </a> </li>"; 
                echo "<li> <a href='#' onclick='toggleGlossary();' > glossary </a> </li>"; 
                echo "<li> <a href='#' class='investclass' onclick='toggleInvest();'> investments </a> </li>";
                echo "<li> <a href='#' class='submitclass' onclick='toggleSubmit();'> submit </a> </li>";

                 }
                   
                ?>
                 </ul>  
          

            <div class="align-right">

                 <?php
                if (isset($_SESSION['user_id'])) {
                    // If 'user_id' session variable is set, assume the user is logged in
                  //  echo "<li><form id='searchform'>  <input type='text' placeholder='Search for startups'></form> </li>";
                    
                    echo "<li> <a href='#' onclick='toggleProfile();'> $userName </a> </li>";
                    echo "<li> <a href='selllogout.php'>logout</a></li>";
                } else {
                    // If 'user_id' session variable is not set, assume the user is not logged in
                    echo "<li><a href='#' class='loginform' onclick='toggleSignup();'>login</a></li>";
                }
                ?>
            </div>



     <style>
        /* Existing styles */
    
        .align-right {
            float: right;
            margin-left: auto;
            display: flex;
            list-style-type: none;
            gap: 10px;
            margin-right: 10px;
            margin-top: 5px;
        }
        
        .companyname {
            font-weight: bold;
        }
        
        .olod { margin-top: 10px;
        }
    </style>
    
           
        </div>
    </div>
    
    <script>
        
        function toggleInvest() {
            document.querySelector('.olod').style.display = 'none'; 
            document.querySelector('.openlisting').style.display = 'none';
            document.querySelector ('.profileus').style.display = 'none';
            document.querySelector('.terms').style.display = 'none';
            document.querySelector('.glossary').style.display = 'none';
            document.querySelector('.invest').style.display = 'block';

        } 
        
        function toggleSubmit() {
            document.querySelector('.olod').style.display = 'block'; 
            document.querySelector('.openlisting').style.display = 'none';
            document.querySelector ('.profileus').style.display = 'none';
            document.querySelector('.terms').style.display = 'none';
            document.querySelector('.glossary').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';


        } 
        
        
        
 
 
        function toggleThreads() {
            document.querySelector('.olod').style.display = 'none';
            document.querySelector('.openlisting').style.display = 'block';
            document.querySelector ('.profileus').style.display = 'none';
            document.querySelector('.terms').style.display = 'none';
            document.querySelector('.glossary').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';


        } 
         
        function toggleProfile() {
            document.querySelector ('.profileus').style.display = 'block';
            document.querySelector('.olod').style.display = 'none';
            document.querySelector('.openlisting').style.display = 'none';
            document.querySelector('.terms').style.display = 'none';
            document.querySelector('.glossary').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';


        }
        
        function toggleTerms() {
             
            document.querySelector ('.profileus').style.display = 'none';
            document.querySelector('.olod').style.display = 'none';
            document.querySelector('.openlisting').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';
            document.querySelector('.terms').style.display = 'block';
            document.querySelector('.glossary').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';

        }
        
        function toggleGlossary() {
             
            document.querySelector ('.profileus').style.display = 'none';
            document.querySelector('.olod').style.display = 'none';
            document.querySelector('.openlisting').style.display = 'none';
            document.querySelector('.terms').style.display = 'none';
            document.querySelector('.glossary').style.display = 'block';
            document.querySelector('.invest').style.display = 'none';
            document.querySelector('.invest').style.display = 'none';

        }
        
        
        
    </script>

    
    <div class="oposl">

<span class="openlisting" id="openlisting">
     <?php

// Replace these variables with your actual database credentials
$servername = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$dbname = "thevirt1_useme";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data from companyprofile table

$sql = "SELECT cp.*, GROUP_CONCAT(CONCAT(fc.whyflag, ' - flagged by <a href=\"mailto:', fc.flagemail, '\">', fc.nameofflagger, '</a>') SEPARATOR '; ') AS flag_reasons
FROM companyprofile cp
LEFT JOIN flagcorp fc ON fc.flaggedcompanyid = cp.id
WHERE NOT EXISTS (
    SELECT 1 FROM buycompany bc WHERE bc.companyid = cp.id
)
AND cp.listeremail <> '$userEmail'
GROUP BY cp.id";


$result = $conn->query($sql);

// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo '<ol>';
    while ($row = $result->fetch_assoc()) {
        $currencySymbol = ($row["myCurrency"] == 'USD') ? '$' : '₦';
        $listedAmount = $currencySymbol . $row["sellingprice"];
        $listedBy = ($row["userLocation"] === "nigeria") ? $row["nigaccountname"] : $row["accountHolderName"];
        $emailLabel = ($row["userLocation"] === "nigeria") ? 'Naira Email:' : 'Dollar Email:';
        $formId = ($row["userLocation"] === "nigeria") ? 'buyFormNaira' : 'buyFormDollars';
        $formClass = ($row["userLocation"] === "nigeria") ? 'stacksub' : 'dollarsub';
        $bidClass = ($row["userLocation"] === "nigeria") ? 'stackbid' : 'dollarbid';
        $bidId = ($row["userLocation"] === "nigeria") ? 'bidFormNaira' : 'bidFormDollars';
        $bidlLabel = ($row["userLocation"] === "nigeria") ? 'Naira Bid Email:' : 'Dollar Bid Email:';
        $bidformaction = ($row["userLocation"] === "nigeria") ? '/nairabid.php' : '/dollarbid.php';
        $buyformaction = ($row["userLocation"] === "nigeria") ? '/nairabuy.php' : '/dollarbuy.php';
        
        echo '<li>
                <span class="companyname">' . $row["companyName"] . '</span><a href="' . $row["companyUrl"] . '" class="dod">(' . $row["companyUrl"] . ')</a>
                <div class="byers">
                    <div class="buys">
                        <small> Description: ' . $row["shortDescription"] . '</small>
                    </div>
                    
<small>
    <div class="menlist">
        | <a href="#" onclick="toggleCompany(' . $row["id"] . ')">View</a> |
        ';

        if (isset($userEmail)) {
            echo '
                <a href="#" onclick="toggleBuy(' . $row["id"] . ')">Buy</a> |
                <a href="#" onclick="toggleBid(' . $row["id"] . ')">Bid</a> |
                <a href="#" onclick="toggleFlag(' . $row["id"] . ')">Flag</a> |
                <a href="#" onclick="toggleViewFlag(' . $row["id"] . ')">View Flags</a>

            ';
        } else {
            echo '
                <a href="#" onclick="alert(\'You cannot buyout a company unless you login!\')">Buy</a> |
                <a href="#" onclick="alert(\'You cannot place a bid for a company unless you login!\')">Bid</a>
                <a href="#" onclick="alert(\'You cannot flag a company unless you login!\')">Flag</a>

            ';
        } 

        echo '
    </div>
</small>

                </div>
                
                <div id="flagDetails_' . $row["id"] . '" style="display: none;">

                    <form id="flagform" method="post" action="sellstartupflag.php">
                    <textarea name="whyflag" cols="30" rows="5" placeholder="Why are you flagging this company?"></textarea>
                    <input type="email" id="flagemail" name="flagemail" value="' . $userEmail . '">
                    <input type="text" id="nameofflagger" name="nameofflagger" value="' . $userName . '" readonly hidden>
                    <input type="text" id="flagcompanyname" name="flagedcompany" value="' . $row["companyName"] . '" readonly hidden>
                    <input type="number" id="flaggedcompanyid" name="flaggedcompanyid" value="' . $row["id"] . '" hidden>
                    
                    
                    <input type="submit" value="Submit Flag">
                     </form>
         </div>
         <br>
   <div id="viewflagDetails_' . $row["id"] . '" style="display: none; font-size:10px;" >
    <ol>';
    // Split the concatenated string of flag reasons into an array
    $flagReasons = explode(';', $row["flag_reasons"]);
    // Iterate through each flag reason and display along with other details
    foreach ($flagReasons as $reason) {
        echo '<li>' . $reason . '</li>';
    }
    echo '</ol>
</div>

  
    <div id="buyDetails_' . $row["id"] . '" style="display: none;">
        <form id="' . $formId . '" method="post" action ="'.$buyformaction.'"> 
        
        <input type="email" id="buyeremail" name="buyeremail" value="' . $userEmail . '"  >
        <input type="text"  id="amountval" name="amount" value="' . $row["sellingprice"] . '" readonly hidden>  
        <input type="text"  id="currencyval" name="currency" value="' . $row["myCurrency"] . '" readonly hidden>  
        <input type="text"  id="companyname" name="beneficiarycompany" value="' . $row["companyName"] . '" readonly hidden> 
        <input type="text"  id="buyername" name="buyername" value="' . $userName . '" readonly hidden> 
        <input type="text"  id="paymentdescr" name="paymentdescr" value="Being payment in full for the purchase of ' . $row["companyName"] . '" readonly hidden>
        <input type="text"  id="selleremail" name="selleremail" value="'. $row["listeremail"] .'" hidden>
        <input type="number" id="companyid" name="companyid" value="'. $row["id"] .'" hidden>

            <br>
            

            <textarea name="shortTextToSeller" cols="30" rows="5" placeholder="Write a short message to the seller"></textarea>
            
            <br>

            <br>
            
<button type="button" onclick="event.preventDefault(); makePayment()">Make Payment</button>

        </form>
    </div>                
                <div id="bidDetails_' . $row["id"] . '" style="display: none;">

    <form id="' . $bidId . '" method="post" action ="'.$bidformaction.'">
            <input type="email" name="bidemail" id="bidemail" value="' . $userEmail . '" hidden>
        <input type="text"  id="currencyval" name="currency" value="' . $row["myCurrency"] . '" readonly hidden>  
        <input type="text"  id="companyname" name="beneficiarycompany" value="' . $row["companyName"] . '" readonly hidden> 
            <input type="text" name="sellingprice" value="' . $row["sellingprice"] . '" hidden>
            <br>
        <input type="text"  id="selleremail" name="selleremail" value="'. $row["listeremail"] .'" hidden>
        <input type="number" id="companyid" name="companyid" value="'. $row["id"] .'" hidden>
        
            <label for="amount"> Enter Bid Amount:</label> <br>
            <input type="number" name="bidvalue" value="" onblur="validateBid(' . $row["id"] . ')" required>
            <br><br>
            <textarea name="shortTextToSeller" cols="30" rows="5" placeholder="Write a short message to the seller"></textarea>
            <br><br>
             
            <input type="submit" class="' . $bidClass . '" value="Submit Bid">
        </form>  

</div>                
                
                <div id="companyDetails_' . $row["id"] . '" style="display: none;" class="comdetails">

                  <br> 

            <div class="floatrow">
                <span class="estabdate"> <b> Date of Establishment </b> <br>' . $row["dateOfEstablishment"] . ' </span><br>
                <span class="incopordate"> <b> Date of Incorporation </b> <br>' . $row["registrationDate"] . ' </span><br>
                <span class="incopordate"> <b> Workforce </b> <br>' . $row["numEmployees"] . ' </span><br>

</div>

<br>
                <div class="addrow">
                
                <span class="financialinfo"> <b> Financial Summary </b> <br>' . $row["financialInfo"] . ' </span><br>
                
                <br>            
    
                <span class="customerbase"> <b> Customer Base </b> <br> ' . $row["customerBase"] . ' </span><br>
        
                <br>       
                <span class="operationaldetails"> <b> Operation Details </b> <br> ' . $row["operationalDetails"] . ' </span><br>
               
                <br> 
                
                </div>
                <div class="floatrow">
                
                <span class="marketpositioning"> <b> Market Share: </b> <br>' . $row["marketPositioning"] . ' </span><br>

                <span class="combineddes"> <b> Declarations: </b> <br> ' . $row["combinedDescription"] . ' </span><br>

                <br>
                <span class="listreason"> <b> Reason for selling: </b> <br>' . $row["listreason"] . ' </span><br>
                
                </div>
                
                <br>
                <span class="additionalinfo"> <b> Additional Info: </b> ' . $row["additionalInfo"] . ' </span><br>
<br>
                <span class="additionalinfo"> <b> Valuations : </b> </span><br>
                <span class="balsheet"> <b> Balance Sheet: </b> ' . generateFileLinks($row["balanceSheetFiles"]) . ' </span><br>
                <span class="cashflow"> <b> Cashflow Statement: </b> ' . generateFileLinks($row["cashFlowStatementFiles"]) . ' </span><br>
                <span class="incomestat"> <b> Income Statement: </b> ' . generateFileLinks($row["incomeStatementFiles"]) . ' </span><br>

                <br>
                   
                   
                   <span class="listedby"> <b> Listed by: </b> ' . $listedBy . ' </span>
                                      <br>

                        <span class="amount"> <b> Selling Price: </b> '. $listedAmount  .' </span>

                   
                 </div>
            </li>';
    }
    echo '</ol>';
} else {
    echo '<p class="newcorp"> No new companies listed, check back very soon! </p>';
}

function generateFileLinks($filesJson) {
    $filesArray = json_decode($filesJson, true);
    $links = '';

    foreach ($filesArray as $type => $fileList) {
        foreach ($fileList as $file) {
            $links .= '<a href="' . $file . '" target="_blank">' . basename($file) . '</a><br>';
        }
    }

    return $links;
}
// Close the connection
$conn->close();
?>



    
<script>
function makePayment() {
    const amount = document.getElementById("amountval").value;
    const currency = document.getElementById("currencyval").value;
    const paymentOptions = "card, banktransfer, ussd";
    const metaSource = "docs-inline-test";
    const customerEmail = document.getElementById("buyeremail").value;
    const customerName = document.getElementById("buyername").value;
    const title = document.getElementById("companyname").value;
    const description = document.getElementById("paymentdescr").value;
    const logo = "https://thevirtualadviser.com.ng/selllogo.png";

    FlutterwaveCheckout({
        public_key: "FLWPUBK_TEST-acc539ebd2b1244fcf32835b66301422-X", // "FLWPUBK_TEST-b0496268630357bdf307256472bed2eb-X", //"FLWPUBK_TEST-f7dd8adeef71fb9e2a522ede8320c0eb-X",
        tx_ref: generateReference(),
        amount: parseInt(amount),
        currency: currency,   
        payment_options: paymentOptions, 
        meta: {
            source: metaSource, 
        },
        customer: {
            email: customerEmail,
            name: customerName,
        },
        customizations: {
            title: title,
            description: description,
            logo: logo,
        },
       callback: function (response) {
    console.log("Full response object:", response);

    // Handle successful payment
    if (response.status === "completed") {
        console.log("Payment successful");
                sendFormData(currency);
    } else {
        console.log("Payment failed with response:", response); 
     }
    },
       }); 
}

function generateReference() {
    return 'tx_' + Math.random().toString(36).substr(2, 9);
}

function sendFormData(currency) {
    const formId = (currency === "NGN") ? 'buyFormNaira' : 'buyFormDollars';
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", form.action, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Handle the response from the server after form submission
                console.log("Form data sent successfully:", xhr.responseText);
            } else {
                console.error("Error sending form data. Status code:", xhr.status);
            }
        }
    };

    xhr.send(formData); 
    console.log(formData);
}
</script>
 
        <script src="https://checkout.flutterwave.com/v3.js"></script>

<script>
// Your JavaScript functions outside the loop

function validateBid(companyId) {
    var sellingPrice = parseFloat(document.querySelector('#bidDetails_' + companyId + ' [name="sellingprice"]').value);
    var bidValueInput = document.querySelector('#bidDetails_' + companyId + ' [name="bidvalue"]');
    var bidValue = parseFloat(bidValueInput.value);
    var minBidValue = 0.2 * sellingPrice;

    if (isNaN(bidValue) || bidValue < minBidValue) {
        alert("Bid value must be at least 20% of the selling value. Minimum bid value is " + minBidValue + ".");
        // Clear bid value
        bidValueInput.value = "";
    }
}

</script>
<script>
    function toggleCompany(companyId) {
        var companyDetails = document.getElementById('companyDetails_' + companyId);
        if (companyDetails.style.display === 'none' || companyDetails.style.display === '') {
            companyDetails.style.display = 'block';
        } else {
            companyDetails.style.display = 'none';
        }
    }
</script>



<script>

    function toggleBid(companyId) {
    var bidDetails = document.getElementById('bidDetails_' + companyId);
    var buyDetails = document.getElementById('buyDetails_' + companyId);
    var flagDetails = document.getElementById('flagDetails_' + companyId);

if (bidDetails.style.display === 'none' || bidDetails.style.display === '') {
    bidDetails.style.display = 'block';
    buyDetails.style.display = 'none';
        flagDetails.style.display = 'none';

} else {
    bidDetails.style.display = 'none';
    }
}

    function toggleBuy(companyId) {
var bidDetails = document.getElementById('bidDetails_' + companyId);
var buyDetails = document.getElementById('buyDetails_' + companyId);
    var flagDetails = document.getElementById('flagDetails_' + companyId);
    var viewflagDetails = document.getElementById('viewflagDetails_' + companyId);

if (buyDetails.style.display === 'none' || buyDetails.style.display === '') {
    buyDetails.style.display = 'block';
    bidDetails.style.display = 'none';
    flagDetails.style.display = 'none';
    viewflagDetails.style.display = 'none';
} else {
    buyDetails.style.display = 'none';
    }

}


function toggleFlag(companyId) {
    var bidDetails = document.getElementById('bidDetails_' + companyId);
    var buyDetails = document.getElementById('buyDetails_' + companyId);
    var flagDetails = document.getElementById('flagDetails_' + companyId);
        var viewflagDetails = document.getElementById('viewflagDetails_' + companyId);

    if (flagDetails.style.display === 'none' || flagDetails.style.display === '') {

    flagDetails.style.display = 'block';
    buyDetails.style.display = 'none';
    viewflagDetails.style.display = 'none';
    bidDetails.style.display = 'none';
} else {
    flagDetails.style.display = 'none';
    }
    
    
}
 
function toggleViewFlag(companyId) {
    var bidDetails = document.getElementById('bidDetails_' + companyId);
    var buyDetails = document.getElementById('buyDetails_' + companyId);
    var flagDetails = document.getElementById('flagDetails_' + companyId);
    var viewflagDetails = document.getElementById('viewflagDetails_' + companyId);
    if (viewflagDetails.style.display === 'none' || viewflagDetails.style.display === '') {
    
    viewflagDetails.style.display = 'block';
    flagDetails.style.display = 'none';
    buyDetails.style.display = 'none';
    bidDetails.style.display = 'none';
} else {
    viewflagDetails.style.display = 'none';
    }
    
    
}
</script>


       </span>


    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>
    
    
    <style>
    
    .newcorp {
                    margin: 20px;

    }
        
         .profile1 {
            margin: 20px;
            max-width: 600px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        a {
            text-decoration: none;
           
        }
    </style>
        <div class="profileus" style="display:none">
        <div class="profile1">
            
            <?php
    $servername = "localhost";
    $username = "thevirt1_useme";
    $password = "1]U6jf77Sl+WMj";
    $dbname = "thevirt1_useme";

    // Create a database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start or resume the session
session_start();

// Get the email from the session
$email = $_SESSION["user_email"];

// Prepare and execute the SQL query
$sql = "SELECT * FROM sellsignup WHERE email = '$email'";
$result = $conn->query($sql);

// Initialize variables
$username = $fullName = $dateOfBirth = "";

// Check if there are results
if ($result->num_rows > 0) {
    // Assuming there is only one result for the given email
    $row = $result->fetch_assoc();

    // Store data in variables
    $username = $row['username'];
    $fullName = $row['fullname'];
    $dateOfBirth = $row['date_of_birth'];
}

// Close the database connection
$conn->close();

// Use the retrieved data outside the loop as needed
echo "<p>Username: <span>$username</span></p>";
echo "<p>Email: <span>$email</span></p>";
echo "<p>Full Name: <span>$fullName</span></p>";
echo "<p>Date of Birth: <span>$dateOfBirth</span></p>";
?>
            
       <!-- <p> Naira Account Balance: <span> N100</span>  <span><button onclick="alert('Perform action for ABC Corp')">Withdraw</button> </span></p>
        <p> Dollar Account Balance: <span> $100</span> <button onclick="alert('Perform action for ABC Corp')"> Withdraw</button></p> -->
        <?php
// Replace these variables with your actual database credentials
$servername = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$dbname = "thevirt1_useme";

// Get user email from the session
$userEmail = $_SESSION["user_email"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data from companyprofile table for the logged-in user using prepared statement
$stmt = $conn->prepare("SELECT * FROM companyprofile WHERE listeremail = ? AND id NOT IN (SELECT companyid FROM buycompany WHERE companyid = companyprofile.id)");
$stmt->bind_param("s", $userEmail);
$stmt->execute();

$result = $stmt->get_result();
// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo  '<p> <u>Listed Companies </u></p>';
    echo '<table border="1">
            <tr>
                <th>S/N</th>
                <th>Company</th>
                <th>Listed Amount</th>
                <th>Bids</th>
                <th>Action</th>
            </tr>';

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Calculate currency symbol and listed amount for each row
        $currencySymbol = ($row["myCurrency"] == 'USD') ? '$' : '₦';
        $listedAmount = $currencySymbol . $row["sellingprice"];

     // Count the number of bids for the current company
        $companyID = $row["id"]; 
        $bidCountQuery = "SELECT COUNT(*) as bidCount FROM companybid WHERE companyid = ?";
        $bidStmt = $conn->prepare($bidCountQuery);
        $bidStmt->bind_param("i", $companyID);
        $bidStmt->execute();
        $bidResult = $bidStmt->get_result();
    
        // Check if there are rows in the bid result
        if ($bidResult->num_rows > 0) {
            $bidRow = $bidResult->fetch_assoc();
            $numBids = $bidRow["bidCount"];
        } else {
            $numBids = 0;   
        } 
        echo '<tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["companyName"] . '</td>
                <td>' . $listedAmount . '</td>
                <td>' . $numBids . '</td>
                <td>
                    <button onclick="performEditAction(\'' . $row["companyName"] . '\')">Edit Listing</button>
                    <button onclick="performViewBidsAction(\'' . $row["companyName"] . '\')">View Bids</button>
                </td>
              </tr>';
    }

    echo '</table>';
} else {
    echo "<br>You are yet to list a new company! <br>";
}

// Close the connection and the statement
$stmt->close();
$conn->close();

// JavaScript functions to handle button actions
echo '
<script>
    function performEditAction(companyName) {
        alert("Perform edit action for " + companyName);
        // Add your edit action logic here
    }

    function performViewBidsAction(companyName) {
        alert("Perform view bids action for " + companyName);
        // Add your view bids action logic here
    }
</script>';
?>


<?php
// Replace these variables with your actual database credentials
$servername = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$dbname = "thevirt1_useme";

// Get user email from the session
$userEmail = $_SESSION["user_email"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data from companyprofile table for the logged-in user using prepared statement
$stmt = $conn->prepare("SELECT * FROM buycompany WHERE buyeremail = ?");

$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo  '<p> <u>Purchased Companies </u></p>';
    echo '<table border="1">
            <tr>
                <th>S/N</th>
                <th>Company</th>
                <th>Purchased Amount</th>
                               <th>Action </th>

            </tr>';

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Calculate currency symbol and listed amount for each row
        $curSymbol = ($row["currency"] == 'USD') ? '$' : '₦';
        $purchasedAmount = $curSymbol . $row["amount"];

        echo '<tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["beneficiarycompany"] . '</td>
                <td>' . $purchasedAmount . '</td>
                <td>
                        <button onclick="performEditAction(\'' . $row["beneficiarycompany"] . '\')">Relist</button>
                </td>
              </tr>';
    }

    echo '</table>';
} else {
    echo "<br>You are yet to purchase a company! <br>";
}

// Close the connection and the statement
$stmt->close();
$conn->close();
?>


<?php
// Replace these variables with your actual database credentials
$servername = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$dbname = "thevirt1_useme";

// Get user email from the session
$userEmail = $_SESSION["user_email"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data from companyprofile table for the logged-in user using prepared statement
$stmt = $conn->prepare("SELECT * FROM companybid WHERE bidemail = ? AND companyid NOT IN (SELECT companyid FROM buycompany WHERE companyid = companybid.companyid)");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo  '<p> <u> Biddings </u></p>';
    echo '<table border="1">
            <tr>
                <th>S/N</th>
                <th>Company</th>
                <th>Bid Amount</th>
                <th>Action</th>

            </tr>';

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Calculate currency symbol and listed amount for each row
        $bidSymbol = ($row["currency"] == 'USD') ? '$' : '₦';
        $bidValue = $bidSymbol . $row["bidvalue"];

        echo '<tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["beneficiarycompany"] . '</td>
                <td>' . $bidValue . '</td>
                <td>
                        <button onclick="performEditAction(\'' . $row["beneficiarycompany"] . '\')">Change Bid</button>
                </td>
              </tr>';
    }
   
    echo '</table>';
} else {
    echo "<br> You haven't made a bid for a company! <br>";
}

// Close the connection and the statement
$stmt->close();
$conn->close();
?>

        <br>
        <a href="#" onclick="openForm()">Update Profile</a>
    </div>

    <!-- Popup form -->
    <div class="pop up-form" id="myForm" style="display:none">
        <form class="loginclassx" method="post" enctype="multipart/form-data" id="formsell"  action="sellstartupupd.php">
         
         
    <div class="colomn111">
         <!-- Add your form fields here -->
        <div class="row223">
            
        
            <label>Full Name:</label>
            <input type="text" id="fname" name="fullname" required><br>
             
             
             
            <label for="business_address"> Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        
        <div class="row223">
            
            <label for="lname">Date of Birth:</label>
            <input type="date" id="dateofbirth" name="date_of_birth"><br> 
            
            <label> Mobile Number: </label>
            <input type="tel" name="mobile" placeholder="+234">

        </div>

        <div class="row223"> 

            <label> Nationality: </label>
            <select id="countrypeople" name="country"></select>
            
            <label> Gender: </label>

            <select id="gender" name="gender"> 
                <option value="male"> Male </option>
                <option value="female"> Female </option>

            </select>   
</div>
        </div>
                    <label> Social Handles: </label> 
                    <input type="url" name="socialmedia" placeholder="e.g https://facebook.com/foundersname">
                     <input type="email" name="email" hidden readonly value="<?php echo $userEmail;?>">

<div class="signup">
            <button type="submit"> Submit </button>
            <button type="button" onclick="closeForm()">Close</button>
            </div>
        </form>
    </div>
    
    </div>
        
        <div class="olod form-group" style="display:none;">
            <form action="sellstartupcomplete.php" method="post" class="companyinfx"  enctype="multipart/form-data" id="sellcomplete"> 
                
                <div class="colomn111">
                <div class="row223">
                    
                    <input type="email" value="<?php echo $userEmail?>" name="listeremail" hidden> 
                    <label for="company_name">Company Name:</label> 
                    <input type="text" id="company_name" name="company_name" required>
                    <br>
                    
                     <label for="business_address"> Business Address:</label>
    <input type="text" id="business_address" name="business_address" required>

                    
                   <label for="company_name">Company Url:</label>
                    <input type="url" name="company_url" placeholder="https://">

</div>
 <div class="row223">
                 <label for="registration_number">Registration Number:</label>
    <input type="text" id="registration_number" name="registration_number">

    <label for="registration_date"> Date of Incorporation (Optional):</label>
    <input type="date" id="registration_date" name="registration_date">

<label for="num_employees">Number of Employees:</label>
    <input type="number" id="num_employees" name="num_employees" required>
</div>
</div> 

               <label> Date of Establishment:</label> 
               <input type="date" name="date_of_establishment" required>
               <br> 

               <label> Short Description about your startup:</label>
               <input type="text" name="short_description" placeholder="Short Description" required> 
                                
               <div class="additionalinfo1">

               <legend> Detailed Company Information:</legend>
               <textarea name="financial_info" cols="30" rows="5" placeholder="Provide details on revenue, profit/loss, assets, and liabilities" required></textarea>

               <textarea name="customer_base" cols="30" rows="5" placeholder="Describe the existing customer base and demographics" required></textarea>
               <textarea name="operational_details" cols="30" rows="5" placeholder="Outline the company structure, key personnel, and operational processes" required></textarea>
               <br>

               <textarea name="market_positioning" cols="30" rows="5" placeholder="Include market share, competition analysis, and target market" required></textarea>
        
               <textarea name="terms_of_sale" cols="30" rows="5" placeholder="Please specify the ownership terms for the startup. Indicate whether you are selling full ownership, partial ownership, or a percentage share. Include details about pricing, payment terms, delivery conditions, and any other relevant information to facilitate a smooth transaction." required></textarea>
               
                <textarea id="combined_description" cols="30" rows="5" name="combined_description" rows="8" placeholder="Description of Products/Services, Details on Patents, Trademarks, Copyrights, Legal Issues related to Intellectual Property" required></textarea>

            
            </div>



<textarea name="additional_info" cols="10" rows="5" placeholder="Additional Information (e.g., Any Upcoming Projects or Expansions, Vision for the Company's Future, Any Ongoing Technology Development or Projects)" class="addit"></textarea>


               <br>
<div class="colomn111">             

<div class="row23">
    
      <label for="location">Where is your bank located?*</label>
  
 <select id="nationselect" name="user_location" class="globalloc">    
  <option value="" selected>Please select a location</option>
  <option value="nigeria">Within Nigeria</option> 
  <option value="outside-nigeria">Outside Nigeria</option>
</select>
  
    <label> Select Currency</label>
    <input type="text" name="mycurrency" id="sellcurrency" value="" readonly required>
    
               <label>Selling Price:</label>
               <input type="number" name="sellingprice" placeholder="Selling Price" min="100" step="100" >
               
             </div>
          <div class="row23">
     
                <label for="balance_sheet">Upload Balance Sheet:</label>
                <input type="file" id="balance_sheet" name="balance_sheet[]" accept=".pdf,.doc,.docx">
            
                <label for="cash_flow_statement"> Upload Cash Flow Statement:</label>
                <input type="file" id="cash_flow_statement" name="cash_flow_statement[]" accept=".pdf,.doc,.docx">
                
                
                <label for="income_statement">Upload Income Statement:</label>
                <input type="file" id="income_statement" name="income_statement[]" accept=".pdf,.doc,.docx">
             
            </div>
        
        <div class="row23">              
        <label> Reason for Listing:</label>
        <textarea name="listreason" cols="20" rows="5" placeholder="Why do you want to sell your startup?" required></textarea>

            </div>

</div>

<br>
                <br>

                <div id="save9ja" class="save9ja" style="display: none;">
                     <label for="bank">Bank Name*</label>
                     <select id="bank-code" name="local_bank"></select>
                    
                      <label for="account-number">Account Number*</label>
                     <input type="text" id="account-number" value="" name="account_number" placeholder="e.g 2065970801">
                   
                     <label> Account Type</label>
                    <select name="accountType" id="accounttype">
                       <option value="savings">Savings</option>
                       <option value="checking">Checking</option>
                     </select>

                     <label for="account-name">Account Name (Required)</label>
                     <input type="text" id="account-name" value="" name="account_name_within" class="accountnamenig" placeholder="" readonly>
                     <button type="button" id="verify-btno">Verify Account</button>

                     <p> Account name will be generated after the "Verify Account" button is pressed. </p>
                     <br>
 
        </div>
                     
        <div id="diaspora" class="diaspora" style="display: none;">
                    
                    <label for="country">Country</label>
                    <select id="country" name="bank_country" class="foreigncountry">
                    </select>
               

                <label> Account Name</label>
                <input type="text" id="account-holder-name" name="account_holder_name" placeholder="Input Your Account Name">   
                

                     <label for="bank-account">Bank Account Number:</label> 
                     <input type="text" id="bank-account-number" value="" class="accountforeign" name="bank_account_number"  placeholder="Input Your Bank Account Name">
                     
                     <label for="bank-number">Routing Number</label>
                      <!-- <label for="bank-number">Routing/Sort or IBAN number</label>-->
                   
                     <input type="text" id="bank-number" name="bank_number" placeholder="e.g 234345664">
                     
                     <br>
                     <label> Bank Name </label>
                     <input type="text" name="foreign_bank_name" id="bank-name" placeholder="Generated Bank name" value="" readonly>   

                        
                     <button type="button" id="verify-btn">Verify Bank</button>
                     <p> Bank name will be generated after the "Verify Bank" button is pressed. </p>

                     <br>
            </div>

        <input type="submit" onclick="validateAndSubmit()" value="Submit">


            </form>
            
        </div>
        
        
        
    <script>
 
function validateAndSubmit() {
    var sellCurrencyInput = document.getElementById('sellcurrency').value;

    // Check if the value is not blank
    if (sellCurrencyInput.trim() === '') {
        alert('Please ensure you select your bank location.');
    } else {
        // Perform any other actions or submit the form
        document.getElementById('formsell').submit();
    }
}

    </script>
        
        
        <style>
            
            .terms, .glossary { margin: 20px;}
            .subhead {
                font-weight: bold;
            }
                .floatrow {
            
                    width: 80%;
                    display: flex;
                    gap: 10px;
                    
                }
                
                .addrow {
                     width: 80%;
                    
                }
            
        </style>
        <div class="terms" style="display:none;">
    
    
    <h4>Sell Your Startup - Terms and Conditions</h4>

    <section>
        <p>1. Acceptance of Terms</p>
        <p>By accessing or using the "Sell Your Startup" website (the "Platform"), you agree to comply with and be bound by these Terms and Conditions. If you do not agree with any part of these terms, you may not access the Platform.</p>
    </section>

    <section>
        <p>2. Description of Service</p>
        <p>"Sell Your Startup" provides a platform for connecting startup owners with potential buyers. Users can list their startups for sale, browse listings, and communicate with other users. The platform also offers additional features, including but not limited to messaging, payment processing, and user verification.</p>
    </section>

    <section>
        <p>3. User Registration</p>
        <p>To access certain features of the Platform, you may be required to register for an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</p>
    </section>

    <section>
        <p>4. User Responsibilities</p>
        <p>a. Listing Content: Users are responsible for the accuracy and legality of the content they submit when creating listings. "Sell Your Startup" reserves the right to remove any content that violates these terms.</p>
        <p>b. Communication: Users agree to communicate respectfully and professionally with other users. Harassment, discrimination, or any form of abusive behavior will not be tolerated.</p>
        <p>c. Payment Processing: If applicable, users agree to comply with the payment processing terms provided by "Sell Your Startup" or its designated third-party payment processor.</p>
    </section>

    <section>
        <p>9. Governing Law</p>
        <p>These Terms and Conditions are governed by and construed in accordance with the laws of Nigeria.</p>
    </section>

      <p>By using "Sell Your Startup," you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>

    
    
</div>


<div class="invest" style="display:none">
    
<form action="sellstartupcomplete.php" method="post" class="companyinfx"  enctype="multipart/form-data" id="sellcomplete"> 
                
                <div class="colomn111">
                <div class="row223">
                    
                    <input type="email" value="<?php echo $userEmail?>" name="listeremail" hidden> 
                    <label for="company_name">Company Name:</label> 
                    <input type="text" id="company_name" name="company_name" required>
                    <br>
                    
                     <label for="business_address"> Business Address:</label>
    <input type="text" id="business_address" name="business_address" required>

               <textarea name="market_positioning" cols="30" rows="5" placeholder="Include market share, competition analysis, and target market" required></textarea>
    <label for="investment_amount">Investment Amount (USD):</label><br>
        <input type="number" id="investment_amount" name="investment_amount" min="1000" step="1000" required><br><br>
        
        <label for="investment_interests">Investment Interests:</label><br>
        <textarea id="investment_interests" name="investment_interests" rows="4" required></textarea><br><br>
        
        <label for="additional_info">Additional Information:</label><br>
        <textarea id="additional_info" name="additional_info" rows="4"></textarea><br><br>
        
        <label for="experience">Investment Experience (if any):</label><br>
        <textarea id="experience" name="experience" rows="4"></textarea><br><br>
        
        <label for="preferred_industries">Preferred Industries:</label><br>
        <select id="preferred_industries" name="preferred_industries[]" multiple required>
            <option value="Technology">Technology</option>
            <option value="Healthcare">Healthcare</option>
            <option value="Finance">Finance</option>
            <option value="Retail">Retail</option>
            <option value="Hospitality">Hospitality</option>
            <!-- Add more options as needed -->
        </select><br><br>
        
        <label for="investment_timeline">Investment Timeline:</label><br>
        <input type="text" id="investment_timeline" name="investment_timeline" placeholder="E.g., Within 6 months" required><br><br>
        
        
        <input type="number" 60:40 split> 
        
                <input type="submit" value="Submit Inquiry">

                    </div></div>
                    </form>
    
</div>



<div class="glossary" style="display:none">
      
      
    <h4>Sell Your Startup - Glossary of Terms </h4>

    <p class="subhead">1. Seller</p>
    <p>The existing owner or entity selling the company.</p>

    <p class="subhead">2. Buyer</p>
    <p>The individual or entity acquiring the ownership and control of the company.</p>

    <p class="subhead">3. Sale Contract</p>
    <p>A legally binding agreement outlining the terms and conditions of the company sale.</p>

    <p class="subhead">4. Variation</p>
    <p>Changes or modifications to the terms of the sale contract, requiring written agreement.</p>

    <p class="subhead">5. Representations</p>
    <p>Statements made by the seller about the company's status, assets, or liabilities.</p>

    <p class="subhead">6. Order Acceptance</p>
    <p>The formal acceptance or rejection of the buyer's offer to purchase the company.</p>

    <p class="subhead">7. Pricing Transparency</p>
    <p>The effort to maintain clarity and openness in disclosing the costs and financial aspects of the company.</p>

    <p class="subhead">8. Payment</p>
    <p>The financial transaction involving the transfer of funds for the purchase of the company.</p>

    <p class="subhead">9. Transfer of Ownership</p>
    <p>The process of legally and officially changing the control and ownership of the company from the seller to the buyer.</p>

    <p class="subhead">10. Warranties</p>
    <p>Assurances or guarantees provided by the seller regarding the legal and financial health of the company.</p>

    <p class="subhead">11. Force Majeure</p>
    <p>Unforeseen circumstances beyond the control of either party that may impact the sale process.</p>

    <p class="subhead">12. Cancellation/Returns</p>
    <p>Policies and procedures regarding the cancellation of the sale or potential return of assets.</p>

    <p class="subhead">13. Modification of Terms</p>
    <p>Changes to the agreed-upon terms of the sale contract, requiring written approval.</p>

    <p class="subhead">14. Notices</p>
    <p>Formal written or email communications between the buyer and seller regarding the sale.</p>

    <p class="subhead">15. Governing Laws</p>
    <p>Disputes governed by Nigerian laws; legal actions in Lagos State, Ikeja Judicial Division.</p>

    <p class="subhead">16. Severability</p>
    <p>The ability to isolate and address specific terms in the event that one provision of the contract is deemed invalid or unenforceable.</p>

    <p class="subhead">17. Intellectual Property</p>
    <p>Any proprietary information, trademarks, or copyrights associated with the company.</p>

    <p class="subhead">18. No Third-Party Benefit</p>
    <p>Clarification that the benefits and obligations outlined in the contract are intended solely for the buyer and seller.</p>

    <p class="subhead">19. Accuracy</p>
    <p>The degree to which information provided in the sale contract reflects the true state of the company.</p>

    <p class="subhead">20. Revisions</p>
    <p>Changes or updates made to the terms and conditions of the sale contract.</p>

    <p class="subhead">21. Entire Agreement</p>
    <p>Confirmation that the terms outlined in the sale contract constitute the complete understanding between the buyer and seller, superseding any prior discussions or agreements.</p>

</div>
    </div>
    
    
    

</div>

      

<div class="authprocess">


 <form action="sellstartupsignup.php" class="signupclass" style="display:none;" method="post">     
        <div class="odkl">   
        <p>Signup</p>
         </div>

        <input type="text" placeholder="username" value="" name="username" required>
        <input type="text" placeholder="email" name="email" required> 
        <input type="password" placeholder="password" name="password" required>
        <input type="password" placeholder="comfirm password" name="confirmpassword" required>
        
        <br>
        <div class="signup">
        <button type="submit" class="signupbutton"> Signup</button>
        <a href="#" onclick="toggleLogin();" class="signing"> login </a>
        </div>

    </form>

    <style> 

    .authprocess {
        margin: 100px auto;
        width: 20%;
        border-radius: 4px;

        background-color: rgba(253, 250, 250, 0.89);
        height: auto;
    }

     .odkl {
            background-color: rgb(255, 78, 8);
            font-size: 10pt;
            width: 100%;
            margin-top:-13px;
        }

        .odkl p {
            color: black;
            margin-left: 10px;
            padding: 5px; 
               }
       
               .companyinfx {
    width: 65%;
}
.colomn111 {
    display: flex;
    flex-direction: row;
    gap: 10px;
}

@media screen and (max-width: 768px) {
    /* Styles for screens up to 768px wide */
    .companyinfx {
        width: 80%; /* Adjust the width for smaller screens */
    }
}

@media screen and (max-width: 480px) {
    /* Styles for screens up to 480px wide */
    .companyinfx {
        width: 100%; /* Adjust the width for even smaller screens */
    }
}


@media screen and (max-width: 768px) {
    /* Styles for screens up to 768px wide */
    .odk, .ayscontainer{
        width: 110%; /* Adjust the width for smaller screens */
    }
}

@media screen and (max-width: 768px) {
    /* Styles for screens up to 768px wide */
    .form-group textarea{
        width: 80%; /* Adjust the width for smaller screens */
    }
}


    .loginclass input,  .loginclass textarea,  .loginclass select,  .loginclass label {
            margin-bottom: 10px;
            width: 90%;
            margin-left: 10px;

        }   
        
        
        .loginclassx input,  .loginclassx textarea,  .loginclassx select,  .loginclassx label {
            margin-bottom: 10px;
            width: 90%;
            margin-left: 10px;

        }  
        
        .loginclassx {
            margin-left: 10px;
        }
        

        .forgetclass input {
            
            margin-bottom: 10px;
            width: 90%;
            margin-left: 10px;
        }
        .signupclass input {
             margin-bottom: 10px;
            width: 90%;
            margin-left: 10px;


        } 


.signup button { 
    
    margin-left: 10px;
}        
 @media screen and (max-width: 768px) {
    /* Styles for screens up to 768px wide */
    .signupclass button, .authprocess {
        width: 80%; /* Adjust the width for smaller screens */
    }
}

@media screen and (max-width: 480px) {
    /* Styles for screens up to 480px wide */
    .signupclass button {
        width: 100%; /* Adjust the width for even smaller screens */
    }
}       
        
.addit {
    width: 95%;
}
        .form-group {
   
    align-items: center; /* Optional: Align items vertically in the center */
    margin-left: 20px;

}

.form-group input, .form-group select, .form-group textarea{
    margin-bottom: 10px;
}
.form-group label {
    margin-right: 10px; /* Optional: Add some spacing between label and input */
    display: flex;
    flex-direction: row;
}


        .signup {
            display: flex;
            gap: 10px;
        }
        
        .signupbutton {
            width:  30%;
        }

        .signing {
            color: gray
        }
    </style>

    <form action="sellstartupslogin.php" class="loginclass"  style="display:none" method="post">
        <div class="odkl">    
            <p>Login</p>
        </div>
        <input type="text" placeholder="email" name="email" required>
        <input type="password" placeholder="password" name="password" required>
        <br>
        
    <div class="signup">
        <button type="submit" class="loginbutton"> Login </button> 
        <a href="#" onclick="toggleForget();" class="signing"> forgot password?</a>
    </div>
    </form> 
     
     
     <form action="forgetpassword.php" class="forgetclass" style="display:none" method="post">
        <div class="odkl">    
            <p>Forget Password</p>
        </div>
        <input type="text" placeholder="email" name="email" required>
        <br>
        
    <div class="signup">
        <button type="submit" class="retrievebutton"> Retrieve </button> 
                <a href="#" onclick="toggleLogin();" class="signing"> login

    </div>
    </form>
     
</div> 



<script>
    const bankCodeDropdown = document.querySelector('#bank-code');
    const accountNumberInput = document.querySelector('#account-number');
    const accountNameInput = document.querySelector('#account-name');
    const verifyButton = document.querySelector('#verify-btno');
    
    // Make an AJAX request to retrieve a list of banks
    fetch('https://api.paystack.co/bank')
      .then(response => response.json())
      .then(banks => {
        // Add each bank as an option in the dropdown
        banks.data.forEach(bank => {
          const option = document.createElement('option');
          option.value = `${bank.code}-${bank.name}`;
          option.text = `${bank.name}`;
          bankCodeDropdown.appendChild(option);
        });
      });
    
    // Listen for a click event on the verify button
    verifyButton.addEventListener('click', () => {
      // Retrieve the selected bank code and account number
      const selectedValue = bankCodeDropdown.value;
      const [bankCode, bankName] = selectedValue.split('-');
      const accountNumber = accountNumberInput.value;
    
      // Make an AJAX request to verify the account number
    
    fetch(`https://api.paystack.co/bank/resolve?account_number=${accountNumber}&bank_code=${bankCode}`, {
      headers: {
        Authorization: 'Bearer sk_live_5117459ebcf8aec8db565b90b47da98c2ed700d9', // Replace with your own secret API key
      },
    })
      .then(response => response.json())
      .then(result => {
        if (result.status) {
          const accountName = result.data.account_name;
          accountNameInput.setAttribute('value', accountName); // Set the value of the account name input field
          accountNameInput.value = accountName; // Set the value property as well (backup option)
        } else {
          alert(result.message);
        }
      });
    
    });
    </script>
  

<script>
document.addEventListener("DOMContentLoaded", function () {
    const nationSelect = document.querySelector("#nationselect");
    const save9jaDiv = document.querySelector("#save9ja");
    const diasporaDiv = document.querySelector("#diaspora");
    const sellCurrencyInput = document.getElementById("sellcurrency");
    const foreignCountry = document.querySelector("#foreigncountry");
    
    nationSelect.addEventListener("change", function () {
        if (this.value === "") {
            save9jaDiv.style.display = "none";
            diasporaDiv.style.display = "none";
            sellCurrencyInput.value = "";
            foreignCountry.value = "";
        } else if (this.value === "nigeria") {
            save9jaDiv.style.display = "block"; 
            diasporaDiv.style.display = "none";
            sellCurrencyInput.value = "NGN";
            foreignCountry.value = "";
        } else if (this.value === "outside-nigeria") {
            save9jaDiv.style.display = "none";
            diasporaDiv.style.display = "block";
            sellCurrencyInput.value = "USD";
        }
    });
});
</script>




<script>
    fetch('https://restcountries.com/v2/all')
    .then(response => response.json())
    .then(data => {
      const countries = data
        .filter(country => country.alpha2Code.toUpperCase() === 'US') // only include United States
        .map(country => {
          const countryCode = country.alpha2Code.toUpperCase();
          return {
            name: country.name,
            code: countryCode
          }
        });
      const select = document.getElementById('country');
      countries.sort((a, b) => a.name.localeCompare(b.name)).forEach(country => {
        const option = document.createElement('option');
        option.value = country.code;
        option.text = country.name;
        select.appendChild(option);
      });
    });
  </script>
  
  
  <script>
    fetch('https://restcountries.com/v2/all')
    .then(response => response.json())
    .then(data => {
      const countries = data
        .filter(country => country.alpha2Code.toUpperCase()) // only include United States
        .map(country => {
          const countryCode = country.alpha2Code.toUpperCase();
          return {
            name: country.name,
            code: countryCode
          }
        });
      const select = document.getElementById('countrypeople');
      countries.sort((a, b) => a.name.localeCompare(b.name)).forEach(country => {
        const option = document.createElement('option');
        option.value = country.name;
        option.text = country.name;
        select.appendChild(option);
      });
    });
  </script>
  
  
  
  
  <script>
  const countryInput = document.getElementById('country');
  const bankNumberInput = document.getElementById('bank-number');
  const bankAccountNumberInput = document.getElementById('bank-account-number');
  const accountHolderNameInput = document.getElementById('account-holder-name');
  const bankNameInput = document.getElementById('bank-name');
  const verifyBtn = document.getElementById('verify-btn');
  
  countryInput.addEventListener('change', () => {
    if (countryInput.value === 'US') {
      bankNumberInput.placeholder = 'Routing Number';
    } else if (countryInput.value === 'GB') {
      bankNumberInput.placeholder = 'Sort Code';
    } else if (countryInput.value === 'AU') {
      bankNumberInput.placeholder = 'BSB Number';
    }
  });
  
  verifyBtn.addEventListener('click', () => {
    const country = countryInput.value;
    let bankNumber = bankNumberInput.value.replace(/\s/g, ''); // Remove any spaces in the bank number input
    const bankAccountNumber = bankAccountNumberInput.value;
    
    let currency;
    let bankNumberField;
    
    if (country === 'US') {
      bankNumberField = 'routing_number';
      currency = 'USD';
    } else if (country === 'GB') {
      bankNumberField = 'sort_code';
      currency = 'GBP';
    } else if (country === 'AU') {
      bankNumberField = 'bsb_number';
      currency = 'AUD';
    }
    
    if (country === 'US' && bankNumber.length !== 9) {
      alert('Routing number must be 9 digits long');
      return;
    } else if (country === 'GB' && bankNumber.length !== 8 && bankNumber.length !== 6) {
      alert('Sort code must be 6 or 8 digits long');
      return;
    } else if (country === 'AU' && bankNumber.length !== 6) {
      alert('BSB number must be 6 digits long');
      return;
    }
    
  stripe.createToken('bank_account', {
    country: country,
    account_number: bankAccountNumber,
    currency: currency,
    account_holder_name: accountHolderNameInput.value,
    account_holder_type: 'individual',
    ...(bankNumberField && { [bankNumberField]: bankNumber }),
  }).then((result) => {
    // Handle the result from the Stripe API
    if (result.error) {
      console.error(result.error.message);
      // Display error message to user
    } else {
      // Update account holder name and bank name fields with API response
      bankNameInput.setAttribute('value', result.token.bank_account.bank_name);
      // Display success message to user
    }
  });
  
  });
  </script>

</body>
</html>