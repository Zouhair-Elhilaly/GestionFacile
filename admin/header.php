<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>systeme de gestion du stock</title>
    <link rel="stylesheet" href="style.css">
     <link rel="stylesheet" href="css/header.css">
         <!-- CSS SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>


        <!-- JS SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <!-- JS jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- <link rel="stylesheet" href="css/designe_navbar_header.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
</head>
<body>
    <header>
        <div class="logo_and_close">
            <div class="logo_admin">
                <img src="" alt="jpg">
                <h3 class="title_dashboard">
                    dashboard
                </h3>
            </div>
            <div class="close_tag">
                <button id="closebtn" onclick="closeNavbar()" >=</button>
            </div>
        </div>
        <div class="left_admin_header">
          <button class="mode">
            litgh
          </button>
          <ul>
            <li id="notification">notification</li>
            <ul class="menu_notification" >
                <li>not1</li>
                <li>not2</li>
                <li>not3</li>
                <li>not4</li>
            </ul>
          </ul>
          <!-- profile -->
          <img src="" alt="(profile )">
        </div>
    </header>
    <div class="container_admin">
