<?php
session_start();

include("connection.php");
include("functions.php");


if($_SERVER['REQUEST_METHOD'] == "POST")
{

    $deal_id = $_POST['deal_id'];
    $deal_status_init = $_POST['deal_status'];
    $partner_email = $_SESSION['partner_email'];
    $partner_name = $_SESSION['user_name'];
    $partner_organization = $_SESSION['partner_organization'];

    //status placeholders

    $p1 = "Lead Generated";
    $p2 = "Product Demonstration Completed";
    $p3 = "Proof of Value";
    $p4 = "Quotes Shared";
    $p5 = "Technical Win";
    $p6 = "Business Win";
    $p7 = "Won";
    $p8 = "Differed";
    $p9 = "Lost";



    if(!empty($deal_id) && !empty($deal_status_init))
    {
      //saving to database
      $deal_status_query = "SELECT * from deals WHERE deal_id = '$deal_id'";
      $deal_status_checker = mysqli_query($con, $deal_status_query);


      $row = mysqli_fetch_assoc($deal_status_checker);
      $name_customer = $row["name_customer"];
      echo $row["status"];


        $partner_organization_checker = $_SESSION['partner_organization'];
        $partner_priv = $_SESSION['partner_priv'];
        if ($partner_priv == '1') {
            $query = "SELECT * FROM deals WHERE deal_id = '$deal_id' and partner_organization = '$partner_organization_checker'";
            $result = mysqli_query($con, $query);
          }

        elseif($partner_priv == '2'){
          $query = "SELECT * FROM deals WHERE deal_id = $deal_id and partner_email = '$partner_email';";
          $result = mysqli_query($con, $query);
        }

        if ($result && mysqli_num_rows($result)>0) {

          if($deal_status_init == 'ACTIVE'){
            $deal_status = $_POST['subcategory'];
            if(!empty($deal_status)){
              if($row["status"] == $deal_status){
                echo "<script>alert(\"Deal status already " . $deal_status .  "!!\")</script>";
              }
              elseif ($row["status"] == 'Inactive' || $row["status"] == "Requested") {
               $update_query = "UPDATE deals SET status = 'Requested', expiry_date = NULL WHERE deal_id = '$deal_id';";
               mysqli_query($con, $update_query);
               if ($partner_priv == '1') {
                   $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_organization = '$partner_organization' AND (status = 'Requested' OR status = 'Inactive');";
                 }
               elseif($partner_priv == '2'){
                 $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_email = '$partner_email' AND (status = 'Requested' OR status = 'Inactive');";
               }

               $result_check_query = mysqli_query($con, $check_query);
               $row = mysqli_num_rows($result_check_query);
               if($row == 1){
                 approval_req_galaxkey($partner_name, $partner_email, $deal_id, $partner_organization,$deal_status,$name_customer);
                 approval_req_partner($partner_email, $deal_id);
                   // echo "<script>alert(\"Deal has been updated\")</script>";
                   //echo '<script>alert(\" \")</script>';
                  echo '<script>alert("You tried to change a requested/inactive deal. Please wait till it is approved.\nClick OK.")</script>';
                }
                else{
                  echo "<script>alert(\"Error has occured! before mailing\")</script>";
                }

                //conditions for not reverting status

              }
              elseif ($row["status"] == $p2 && $deal_status == $p1) {
                  echo "<script>alert(\"You can't change the status from " . $p2 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p3 && ($deal_status == $p1 || $deal_status == $p2 )) {
                  echo "<script>alert(\"You can't change the status from " . $p3 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p4 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 )) {
                  echo "<script>alert(\"You can't change the status from " . $p4 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p5 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 || $deal_status == $p4 )) {
                  echo "<script>alert(\"You can't change the status from " . $p5 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p6 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 || $deal_status == $p4 || $deal_status == $p5 )) {
                  echo "<script>alert(\"You can't change the status from " . $p6 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p7 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 || $deal_status == $p4 || $deal_status == $p5 || $deal_status == $p6 )) {
                  echo "<script>alert(\"You can't change the status from " . $p7 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p8 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 || $deal_status == $p4 || $deal_status == $p5 || $deal_status == $p6 || $deal_status == $p7 )) {
                  echo "<script>alert(\"You can't change the status from " . $p8 . " to " . $deal_status . "\")</script>";
              }
              elseif ($row["status"] == $p9 && ($deal_status == $p1 || $deal_status == $p2 || $deal_status == $p3 || $deal_status == $p4 || $deal_status == $p5 || $deal_status == $p6 || $deal_status == $p7 || $deal_status == $p8 )) {
                  echo "<script>alert(\"You can't change the status from " . $p9 . " to " . $deal_status . "\")</script>";
              }



              else{
                $update_query = "UPDATE deals SET status = '$deal_status', expiry_date = NULL WHERE deal_id = '$deal_id';";
                mysqli_query($con, $update_query);
                if ($partner_priv == '1') {
                  $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_organization = '$partner_organization' AND status = '$deal_status';";
                }
                elseif($partner_priv == '2'){
                  $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_email = '$partner_email' AND status = '$deal_status';";
                }
                $result_check_query = mysqli_query($con, $check_query);
                $row = mysqli_num_rows($result_check_query);
                echo $row;
                echo $partner_email;
                if($row == 1){
                  deal_status_changed_galaxkey($partner_email,$partner_name, $deal_id, $partner_organization,$deal_status);
                  deal_status_changed_partner($partner_name, $deal_id,$deal_status,$name_customer,$partner_email);
                  // echo "<script>alert(\"Deal has been updated\")</script>";
                  echo '<script>alert("Thank you for updating the status. You will be notified soon.\nClick OK. ")</script>';
                }
                else{
                  echo "<script>alert(\"Error has occured! here!\")</script>";
                }

              }

            }
            else {
              echo "<script>alert(\"Please enter all the information!\")</script>";
            }


          }

          elseif ($deal_status_init == "INACTIVE") {
            $update_query = "UPDATE deals SET status = '$deal_status_init', expiry_date = NULL WHERE deal_id = '$deal_id';";
            mysqli_query($con, $update_query);
            if ($partner_priv == '1') {
              $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_organization = '$partner_organization' AND status = '$deal_status_init';";
            }
            elseif($partner_priv == '2'){
              $check_query = "SELECT * FROM deals WHERE deal_id = '$deal_id' AND partner_email = '$partner_email' AND status = '$deal_status_init';";

            }
            $result_check_query = mysqli_query($con, $check_query);
            $row = mysqli_num_rows($result_check_query);
            if($row == 1){
              deal_inactivated($partner_email, $deal_id, $partner_organization,$deal_status_init);
              // echo "<script>alert(\"Deal has been updated\")</script>";
              echo '<script>alert("Thank you for updating the status. Deal has been inactivated.\nClick OK. ")</script>';
            }
            else {
              echo "<script>alert(\"Error has occured!\")</script>";
            }

          }

        }
        else {
          echo "<script>alert(\"Deal not approved or error!\")</script>";
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
<script language="javascript" type="text/javascript">
function dynamicdropdown(listindex)
{
document.getElementById("subcategory").length = 0;
switch (listindex)
{
case "ACTIVE" :
document.getElementById("subcategory").style.display= "flex";
document.getElementById("")
document.getElementById("subcategory").options[0]=new Option("Please select the detailed status","");
document.getElementById("subcategory").options[1]=new Option("Lead Generated","Lead Generated");
document.getElementById("subcategory").options[2]=new Option("Product Demonstration Completed","Product Demonstration Completed");
document.getElementById("subcategory").options[3]=new Option("Proof of Value","Proof of Value");
document.getElementById("subcategory").options[4]=new Option("Quotes Shared","Quotes Shared");
document.getElementById("subcategory").options[5]=new Option("Technical Win","Technical Win");
document.getElementById("subcategory").options[6]=new Option("Business Win","Business Win");
document.getElementById("subcategory").options[7]=new Option("Won","Won");
document.getElementById("subcategory").options[8]=new Option("Differed","Differed");
document.getElementById("subcategory").options[9]=new Option("Lost","Lost");
break;
case "INACTIVE" :
document.getElementById("subcategory").style.display= "none";
break;
}
return true;
}
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="assets/css/style.css">
     <title>Change Deal Status</title>
   </head>
   <body>


     <style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');


     *{
       margin: 0;
       padding: 0;
       border: 0;
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
       border-radius: 10px;
       max-height: 95%;
       display: flex;
       flex-direction: column;
       justify-content: center;
       margin: auto;
       width: 35%;
       padding: 20px 35px 25px 35px;
       align-items: center;
       justify-content: center;
       background: #fff;
       -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
       overflow: auto;
     }

     #box h1{
       font-size: 2rem;
       padding: 15px;
     }

     .deal_status{
       width: 25%;
       margin-left: 25px;
     }


     #records{
       /*display: flex;
       flex-direction: column;
       justify-content: space-around;
       align-items: center;
       background-color: yellow;
       height: 25vh;*/
       border-radius: 15px;
       display: flex;
       flex-direction: column;
       align-items: center;
       width: 100%;
       background: #fff;
       margin: 0 auto;
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



     .second_container{
       display: flex;
       width: 100%;
       margin: auto;
       height: 90vh;
       /*background: red;*/
       justify-content: space-between;
      /* background-color: #555;*/
      background: linear-gradient(180deg, #DBDBDB, #EAEAEA);
      background-image: url("qbkls.png");
      background-repeat: repeat;
      overflow: hidden;
     }

     /* #change_status{ */
      /* align-items: center;
       display: flex;
       flex-direction: column;
       justify-content: center;
       width: 30%;
       margin: auto;
       height: auto;*/
       /* display: flex;
       flex-direction: column;
       border-radius: 10px;
       margin: auto;
       width: 35%;
       padding: 20px 35px 25px 35px;
       align-items: center;
       justify-content: center;
       background: #fff;
       -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
       overflow: auto;
     } */

     /* #change_status h1{
       padding-bottom: 20px;
       padding-top: 20px;
     } */

     #button{
       padding: 10px;
       background: linear-gradient(120deg, #e52d27,#b31217);
       border-radius: 25px;
       height: 50px;
       width: 100%;
       color: white;
       display: flex;
       border: none;
                                   justify-content: center;
       font-size: 20px;
       -webkit-box-shadow: 2px 2px 3px -1px rgba(0,0,0,0.35);
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

     .change{
       display: flex;
       flex-direction: column;
       align-items: center;
       justify-content: center;
     }


     .options{
       width: 100%;
       margin: auto;

       display: flex;
                                   flex-direction: column;
       justify-content: center;
     }

     .options select{

       width: 100%;
                                   margin:auto;
     }

     .list-items{
       list-style: none;
       width: 100%;
       display: flex;
       justify-content: space-around;
       align-items: center;
     }


     .list-items li a{
       text-decoration: none;
       color: black;
     }


     .list-items li a:hover{
       background-color: black;
       padding: 5px 7px;
       border-radius: 5px;
       color: white;
                                   margin:auto;
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


.sub_category_div {
display: flex;
flex-direction: column;
justify-content: center;
}

.nav-toggle, .nav-toggle-label{
        display: none;
      }



@media (max-width: 960px){

  .second_container{
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }
  #box{
    margin-block: 15px;
    min-width: 90%;
    min-height: auto;
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
    min-height: auto;
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
       <button onclick="location.href = 'register_deal.php';">Register a Deal</a></button>
       <button onclick="location.href = 'change_status.php';">Change deal Status</a></button>
       <button onclick="location.href = 'upload_docs.php';">Upload Documents</a></button>
       <button onclick="location.href = 'demo.html';">Demo</a></button>


       <div class="downloads">
         <button>Downloads</button>
         <ul>
           <li> <button onclick="location.href = 'https://manager.galaxkey.com/downloads';">Galaxkey Client</button></li>
           <li><button onclick="location.href = 'https://www.galaxkey.com/datasheets/';">Datasheets</button></li>
           <li><button onclick="location.href = 'https://www.galaxkey.com/case-studies/';">Case Studies</button></li>
           <li><button onclick="location.href = 'userguides.html';">User Guide</button></li>
         </ul>
       </div>
       <button onclick="location.href = 'logout.php';">Logout</button>
     </div>
     </nav>

<div class="second_container">

<div id="box">
          <!-- <div id="change_status"> -->
            <h1>Change deal status</h1>
            <form id = "form" method="post">
              <input type="text" name="deal_id" placeholder="Enter Deal ID"><br>
              <br><div class="change" >
                <h3>Select deal status:</h3>
                <br><div class="options">
                  <select name="deal_status" onchange="javascript: dynamicdropdown(this.options[this.selectedIndex].value);">
                                   <option value="Select Deal Status">Select Deal Status</option>
                    <option value="ACTIVE">ACTIVE</option>
                    <option value="INACTIVE">INACTIVE</option>
                  </select><br>


                                   <div class="sub_category_div" id="sub_category_div" >
                                  <br>
                                   <script type="text/javascript" language="JavaScript">
                                   document.write('<select name="subcategory" id="subcategory"><option value="">Please select the detailed status as well</option></select>')
                                   </script>
                                   <noscript>
                                   <br>
                                   <select name="subcategory" id="subcategory" style= "width: 100%;">
                                   <option value="">Please select the detailed status as well</option>
                                   </select>
                                   </noscript>
                                   </div>

              </div>
                                   </div>

              <br><br>
              <input id="button" type="submit" name="" value="Update Record"><br><br>

            </form>


            <a href="register_deal.php">Back</a>


          <!-- </div> -->

        </div>



          <div id="records" style="width:50%; margin:auto;">
            <h2>Your current records are:</h2>
            <table class='keywords'>
              <thead>
                <tr>
                  <th><span>Deal ID</span></th>
                  <th><span>Deal Status</span></th>
                  <th><span>Deal Date</span></th>
                  <th><span>Deal Expiry</span></th>

                  <th><span>Partner Name</span></th>
                  <th><span>Partner Email</span></th>
                  <th><span>Customer Name</span></th>

                  <th><span>Expected Closure</span></th>

                  <th><span>Decision Maker</span></th>
                  <th><span>Designation</span></th>
                  <th><span>Email</span></th>
                  <th><span>Phone number</span></th>

                </tr>

              </thead>
              <tbody>


                                   <?php
                                      $partner_organization_checker = $_SESSION['partner_organization'];
                                      $partner_email_checker = $_SESSION['partner_email'];
                                      $partner_priv = $_SESSION['partner_priv'];
                                      if ($partner_priv == '1') {
                                            $record_query = "SELECT * FROM deals WHERE partner_organization = '$partner_organization_checker'";
                                            $result = mysqli_query($con, $record_query);
                                            if(!$result || mysqli_num_rows($result) == 0)
                                            {
                                              echo "No records found!";
                                            }
                                            else {

                                              while($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr><td>".$row["deal_id"]."</td><td>".$row['status']."</td><td>".$row["deal_date"]."</td><td>".$row["expiry_date"]."</td><td>".$row["partner_name"]."</td><td>".$row["partner_email"]."</td><td>".$row["name_customer"]."</td><td>".$row["expected_closure"]."</td><td>".$row["name_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["email_decision_maker"]."</td><td>".$row["phone_decision_maker"]."</td></tr>";
                                            }
                                          }
                                      }
                                      elseif ($partner_priv == '2') {
                                            $record_query = "SELECT * FROM deals WHERE partner_email = '$partner_email_checker'";
                                            $result = mysqli_query($con, $record_query);
                                            if(!$result || mysqli_num_rows($result) == 0)
                                            {
                                              echo "No records found!";
                                            }
                                            else {

                                              while($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr><td>".$row["deal_id"]."</td><td>".$row['status']."</td><td>".$row["deal_date"]."</td><td>".$row["expiry_date"]."</td><td>".$row["partner_name"]."</td><td>".$row["partner_email"]."</td><td>".$row["name_customer"]."</td><td>".$row["expected_closure"]."</td><td>".$row["name_decision_maker"]."</td><td>".$row["designation_decision_maker"]."</td><td>".$row["email_decision_maker"]."</td><td>".$row["phone_decision_maker"]."</td><td>".$row['status']."</td><td>".$row["deal_date"]."</td><td>".$row["expiry_date"]."</td></tr>";
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




   </body>
 </html>
