<?php
session_start();

include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $partner_name = $_POST['partner_name'];
  $partner_organization = $_POST['partner_organization'];
  $partner_email = $_POST['partner_email'];
  $name_customer = $_POST['name_customer'];
  $implementation_preference = $_POST['implementation_preference'];
  $number_end_users = $_POST['number_end_users'];
  $expected_closure = $_POST['expected_closure'];
  $req_bud = $_POST['req_bud'];
  $name_decision_maker = $_POST['name_decision_maker'];
  $designation_decision_maker = $_POST['designation_decision_maker'];
  $email_decision_maker = $_POST['email_decision_maker'];
  $phone_decision_maker = $_POST['phone_decision_maker'];
  $deal_status = $_POST['deal_status'];

  //echo $partner_name, $partner_email, $partner_organization, $name_customer, $implementation_preference, $number_end_users, $expected_closure, $req_bud, $name_decision_maker, $designation_decision_maker, $email_decision_maker, $phone_decision_maker, $deal_status;


  if(!empty($partner_organization) && !empty($partner_name) && !empty($partner_email) && !empty($name_customer) && !empty($implementation_preference) && !empty($number_end_users) && !empty($expected_closure) && !empty($req_bud) && !empty($name_decision_maker) && !empty($designation_decision_maker) && !empty($email_decision_maker) && !empty($phone_decision_maker) && !empty($deal_status))
  {
    //saving to database
    $deal_id = random_num(6);
    $query = "INSERT INTO deals (deal_id, partner_organization, partner_name, partner_email, name_customer, implementation_preference, number_end_users, expected_closure, req_bud, name_decision_maker, designation_decision_maker, email_decision_maker, phone_decision_maker, status, deal_date, expiry_date) VALUES ('$deal_id', '$partner_organization', '$partner_name', '$partner_email', '$name_customer', '$implementation_preference', '$number_end_users', '$expected_closure', '$req_bud', '$name_decision_maker', '$designation_decision_maker', '$email_decision_maker', '$phone_decision_maker', '$deal_status',CURDATE(),NULL)";
    mysqli_query($con, $query);  //Saving all the data to database


    $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_email = '$partner_email' AND partner_organization = '$partner_organization';";
    $result_check_query = mysqli_query($con, $check_query);
    $row = mysqli_num_rows($result_check_query);
    if($row == 1){
      new_deal_reg_galaxkey($partner_email, $partner_name,$deal_id, $partner_organization);
      new_deal_reg_partner($partner_email, $partner_name, $deal_id, $partner_organization);
      echo "<script>alert(\"Deal has been registered. Thank you\")</script>";
    }
    else{
      echo "<script>alert(\"Error has occured! Deal already exists!\")</script>";
    }
  }
  else {
      echo "<script>alert(\"Please enter all the information!\")</script>";
  }
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Deal Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

</head>
<body>
  <style media="screen">

  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

  *{
    margin: 0;
    border: 0;
    padding: 0;
    box-sizing: border-box;
  }


  body{
    font-family: 'Poppins', sans-serif;
  }


  #text{
    height: 45px;
    border-radius: 20px;
    padding: 15px;
    border: solid thin #aaa;
    width: 100%;
  }

  #button{
    padding: 10px;
    background: linear-gradient(120deg, #e52d27,#b31217);
    border-radius: 25px;
    height: 50px;
    width: 100%;
    color: white;
    display: flex;
    border: none;
    font-size: 20px;
    -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
  }
  #box{
    border-radius: 0px;
    max-height: 95%;
    margin: auto;
    width: 35%;
    padding: 20px 35px 25px 35px;
    align-items: center;
    justify-content: center;
    background: #fff;
    -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
    overflow: auto;
  }

  #box form{
    max-height: 90%;
  }

  .deal_status{
    width: 25%;
    margin-left: 25px;

  }

  #records{
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 50%;
    background: #fff;
    padding: 10px 17px;
    -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
    height: 50vh;
    margin: auto;
    overflow: auto;
  }

  #records h2{
    margin: 15px;
    padding: 15px;
  }

  .keywords{
    margin: 0 auto;
    font-size: 1.2em;
    margin-bottom: 15px;
  }

  .keywords thead{
    cursor: pointer;
    background: #c9dff0;
  }

  .keywords thead tr th {
    font-weight: bold;
    padding: 12px 30px;
    padding-left: 42px;
  }

  .keywords thead tr th span {
    /*padding-right: 20px;*/
    background-repeat: no-repeat;
    background-position: 100% 100%;
  }


  .keywords tbody tr {
    color: #555;
  }

  .keywords tbody tr td {
    text-align: center;
    padding: 15px 10px;
  }


  .containerr{
    display: flex;
    max-width: 100%;
    margin: auto;
    min-height: 100%;
    justify-content: space-around;
    background: linear-gradient(180deg, #DBDBDB, #EAEAEA);
    background-image: url("qbkls.png");
    background-repeat: repeat;
    overflow: hidden;
  }

  .footer-dark {
    padding:50px 0;
    color:#f0f9ff;
    background-color:#2d3436;
  }

  .footer-dark h3 {
    margin-top:0;
    margin-bottom:12px;
    font-weight:bold;
    font-size:16px;
  }

  .footer-dark ul {
    padding:0;
    list-style:none;
    line-height:1.6;
    font-size:14px;
    margin-bottom:0;
  }

  .footer-dark ul a {
    color:inherit;
    text-decoration:none;
    opacity:0.6;
  }

  .footer-dark ul a:hover {
    opacity:0.8;
  }

  @media (max-width:767px) {
    .footer-dark .item:not(.social) {
      text-align:center;
      padding-bottom:20px;
    }
  }

  .footer-dark .item.text {
    margin-bottom:36px;
  }

  @media (max-width:767px) {
    .footer-dark .item.text {
      margin-bottom:0;
    }
  }

  .footer-dark .item.text p {
    opacity:0.6;
    margin-bottom:0;
  }

  .footer-dark .item.social {
    text-align:center;
  }

  @media (max-width:991px) {
    .footer-dark .item.social {
      text-align:center;
      margin-top:20px;
    }
  }

  .footer-dark .item.social > a {
    font-size:20px;
    width:36px;
    height:36px;
    line-height:36px;
    display:inline-block;
    text-align:center;
    border-radius:50%;
    box-shadow:0 0 0 1px rgba(255,255,255,0.4);
    margin:0 8px;
    color:#fff;
    opacity:0.75;
  }

  .footer-dark .item.social > a:hover {
    opacity:0.9;
  }

  .footer-dark .copyright {
    text-align:center;
    padding-top:24px;
    opacity:0.3;
    font-size:13px;
    margin-bottom:0;
  }

  button{
    font-family: "Poppins", sans-serif;
  }


  .dropdown{
    height: 7vh;
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    background-color: #d63031;
  }

  .downloads{
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .downloads ul{
    background-color: rgba(214, 48, 49,1.0);
    position: absolute;
    margin-top: 10px;
    margin-bottom: 0px;
    width: 200px;
    height: 200px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-direction: column;
    list-style: none;
    border-radius: 5px;
    opacity:0;
    transform: scaleY(0);
    transition: all 0.4s;
  }

  .downloads a {
    color: white;
    text-decoration: none;
  }

  .downloads li{

    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bolder;
  }

  .downloads li:hover{
    background-color: rgba(255, 118, 117,1.0);
  }

  .dropdown button, .home{
    background: none;
    border: none;
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: bolder;
    cursor: pointer;
    width: 100%;
  }

  .downloads{
    width: 100%;
  }

  .dropdown button:hover, .home:hover{
    background-color: rgba(255, 118, 117,1.0);
    height: 100%;

  }

  .downloads button:hover{
    background-color: rgba(255, 118, 117,1.0);
    height: 100%;
  }

  .downloads button:focus +ul{
    opacity: 1;
    transform: translateY(110px);
  }

  .logo img{
    width: 20%;
    height: auto;

  }

  .logo{
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #2d3436;

  }

  .logo h2{
    color: white;
  }

  .register{
    /*background-color: yellow;*/
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    padding: 25px;
    font-weight: bolder;
  }


  .nav-toggle, .nav-toggle-label{
    display: none;
  }

  @media (max-width: 960px){

    .containerr{
      display: flex;
      flex-direction: column;
    }
    #box{
      margin-block: 15px;
      min-width: 90%;
      min-height: 100%;
    }

    #box form{
      max-width: 100%;
    }

    .register{
      font-size: 1.75rem;
    }
    .dropdown{
      position: absolute;
      height: 50%;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
    }
    .logo{
      min-height: 10vh;
    }
    .logo img{
      min-width: 35%;
    }
    #records{
      min-width: 90%;
    }
    .keywords{
      font-size: 1.25rem;
    }
    .keywords thead{
      font-size: 1rem;
    }

    .dropdown{
      display: none;
    }

    .nav-toggle{
      display: none;
    }

    .dropdown{
      display: none;
    }

    .nav-toggle:checked ~ .dropdown{
      display: flex;
    }

    .nav-toggle-label{
      position: absolute;
      background-color: white;
      width: 35px;
      height: 5px;
      color: #FFF;
      top: 50px;
      left: 20px;
      display: block;
    }

    .nav-toggle-label span::before,
    .nav-toggle-label span::after{
      background-color: white;
      width: 35px;
      height: 5px;
    }

    .nav-toggle-label span::before,
    .nav-toggle-label span::after{
      content: '';
      position: absolute;
    }

    .nav-toggle-label span::before{
      bottom: 10px;
    }

    .nav-toggle-label span::after{
      top:10px;

    }


    .dropdown button, .home{
      background: none;
      border: none;
      color: white;
      text-decoration: none;
      font-size: 18px;
      font-weight: bolder;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }



  }



  </style>


  <nav>
    <div class="logo">
      <img src="logocolorwhite.png" alt="">
      <h2>Partner Portal</h2>
    </div>

    <input type="checkbox" id='nav-toggle' class="nav-toggle">
    <label for="nav-toggle" class="nav-toggle-label"><span></span></label>

    <div class="dropdown">

      <button onclick="location.href = 'register_deal.php';">Register a Deal</button>
      <button onclick="location.href = 'change_status.php';">Change deal Status</button>
      <button onclick="location.href = 'upload_docs.php';">Upload Documents</button>
      <button onclick="location.href = 'demo.html';">Demo</button>

      <div class="downloads">
        <button>Downloads</button>
        <ul>
          <li> <button onclick="location.href = 'https://manager.galaxkey.com/downloads';">Galaxkey Client</button></li>
          <li><button onclick="location.href = 'https://www.galaxkey.com/datasheets/';">Datasheets</button></li>
          <li><button onclick="ocation.href = 'https://www.galaxkey.com/case-studies/';">Case Studies</button></li>
          <li><button onclick="location.href = 'userguides.html';">User Guide</button></li>
        </ul>
      </div>
      <button onclick="location.href = 'logout.php';">Logout</button>
    </div>
  </nav>

  <div class="containerr">

    <div id="box">
      <form method="post">
        <div class='register'>Register a deal!</div>
        <input id="text" type="text" name="partner_organization" value="<?php
        echo $_SESSION['partner_organization']; ?>" placeholder="Organization" readonly><br><br>
        <input id="text" type="text" name="partner_name" placeholder="User Name" value="<?php
          echo $_SESSION['user_name'] ?>" readonly><br><br>
        <input id="text" type="text" name="partner_email" placeholder="Partner Email"
        value="<?php
        echo $_SESSION['partner_email'];
        ?>"readonly>
        <br><br>

        <input id="text" type="text" name="name_customer" placeholder="Name of Customer"><br><br>
        <label for="implementation_preference" style="color: black;">Preferred Implementation:</label>
        <select class= 'implementation_preference' name="implementation_preference">
          <option value="On-premise">On-Premise</option>
          <option value="Cloud">Cloud</option>
        </select> <br><br>
        <input id="text" type="number" name="number_end_users" min = "1" placeholder="Number of End Users"><br><br>
        <label for="expected_closure" style="color: black;">Expected Quarter/ Date of Closure:</label>
        <select class= 'expected_closure' name="expected_closure">
          <option value="January-February-March">Januray-February-March</option>
          <option value="April-May-June">April-May-June</option>
          <option value="July-August-September">July-August-September</option>
          <option value="October-November-December">October-November-December</option>
        </select> <br><br>
        <label for="req_bud" style="color: black;">Requirement Budgeted:</label>
        <select class= 'req_bud' name="req_bud">
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select> <br><br>
        <input id="text" type="text" name="name_decision_maker" placeholder="Name of Decision Maker"><br><br>
        <input id="text" type="text" name="designation_decision_maker" placeholder="Designation of Decision Maker"><br><br>
        <input id="text" type="email" name="email_decision_maker" placeholder="Email of Decision Maker"><br><br>
        <input id="text" type="tel" name="phone_decision_maker" placeholder="Phone Number of Decision Maker (+123 12345678...)"><br><br>
        <label for="deal_status" style="color: black;">Deal Status:</label>
        <select class= 'deal_status' name="deal_status">
          <option value="Requested">Requested</option>
        </select> <br><br>
        <input id="button" type="submit" name="" value="Submit">
        <br><br>
        <a href="logout.php">Logout</a><br>
        <a href="change_status.php">Change deal status</a>
      </form>
    </div>


    <div id="records">
      <h2>Your current records are:</h2>
      <table class='keywords'>
        <thead>
          <tr>
            <th><span>Deal ID</span></th>
            <th><span>Partner organisation</span></th>
            <th><span>Partner Name</span></th>
            <th><span>Partner Email</span></th>
            <th><span>Customer Name</span></th>
            <th><span>Implementation Preference</span></th>
            <th><span>Number of end users</span></th>
            <th><span>Expected Closure</span></th>
            <th><span>Require Budgeted</span></th>
            <th><span>Decision Maker</span></th>
            <th><span>Designation</span></th>
            <th><span>Email</span></th>
            <th><span>Phone number</span></th>
            <th><span>Deal Status</span></th>
            <th><span>Deal Date</span></th>
            <th><span>Deal Expiry</span></th>
          </tr>

        </thead>
        <tbody>

          <?php
          $partner_organization_checker = $_SESSION['partner_organization'];
          $partner_email_checker = $_SESSION['partner_email'];
          $partner_priv = $_SESSION['partner_priv'];
          if ($partner_priv == '1') {
            $record_query = "SELECT * FROM deals WHERE partner_organization = '$partner_organization_checker' ";
            $result = mysqli_query($con, $record_query);
            if(!$result || mysqli_num_rows($result) == 0)
            {
              echo "<div>No records found!</div>";
            }
            else {

              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>".$row["deal_id"]."</td><td>".$row["partner_organization"]."</td><td>".$row["partner_name"]."</td><td>".$row["partner_email"]."</td><td>".$row["name_customer"]."</td><td>".$row["number_end_users"]."</td><td>".$row["expected_closure"]."</td><td>".$row["req_bud"]."</td><td>".$row["name_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["email_decision_maker"]."</td><td>".$row["phone_decision_maker"]."</td><td>".$row['status']."</td><td>".$row["deal_date"]."</td><td>".$row["expiry_date"]."</td></tr>";
              }
            }
          }
          elseif ($partner_priv == '2') {
            $record_query = "SELECT * FROM deals WHERE partner_email = '$partner_email_checker' ";
            $result = mysqli_query($con, $record_query);
            if(!$result || mysqli_num_rows($result) == 0)
            {
              echo "<div>No records found!</div>";
            }
            else {

              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>".$row["deal_id"]."</td><td>".$row["partner_organization"]."</td><td>".$row["partner_name"]."</td><td>".$row["partner_email"]."</td><td>".$row["name_customer"]."</td><td>".$row["number_end_users"]."</td><td>".$row["expected_closure"]."</td><td>".$row["req_bud"]."</td><td>".$row["name_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["email_decision_maker"]."</td><td>".$row["phone_decision_maker"]."</td><td>".$row['status']."</td><td>".$row["deal_date"]."</td><td>".$row["expiry_date"]."</td></tr>";
              }

            }

          }
          echo "</tbody> </table>";

          ?>
        </div>

      </div>


      <div class="footer-dark">
        <footer>
          <div class="container">
            <div class="row">
              <div class="col-sm-6 col-md-3 item">
                <h3>Connect</h3>
                <ul>
                  <li><a href="https://www.galaxkey.com/contact/contact/">Contact Us</a></li>
                  <li><a href="https://www.galaxkey.com/contact/contact/">Book a demo</a></li>
                  <li><a href="https://www.galaxkey.com/contact/contact/">Try before you buy</a></li>
                </ul>
              </div>
              <div class="col-sm-6 col-md-3 item">
                <h3>About</h3>
                <ul>
                  <li><a href="https://www.galaxkey.com/aboutgalaxkey/">Company</a></li>
                  <li><a href="https://www.galaxkey.com/aboutgalaxkey/our-executive-team/">Executive Team</a></li>
                  <li><a href="https://www.galaxkey.com/aboutgalaxkey/our-investment-team/">Investment Team</a></li>
                </ul>
              </div>
              <div class="col-md-6 item text">
                <h3>Galaxkey</h3>
                <p>Your business deserves the best encryption. Protection for Emails, Documents and Secure file sharing</p>
              </div>
              <div class="col item social"><a href="https://en-gb.facebook.com/galaxkey/"><i class="icon ion-social-facebook"></i></a><a href="https://twitter.com/galaxkey"><i class="icon ion-social-twitter"></i></a><a href="https://www.linkedin.com/company/galaxkey-limited/"><i class="icon ion-social-linkedin"></i></a></div>
            </div>
            <p class="copyright">Galaxkey Limited © All rights reserved.</p>
          </div>
        </footer>
      </div>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </body>
    </html>
