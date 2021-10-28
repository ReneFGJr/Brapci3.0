<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon"  href="<?php echo URL.'/img/favicon.png';?>" sizes="76x76">
  <link rel="icon" href="<?php echo URL.'/img/favicon.png';?>" type="image/png">
  <link rel="icon" href="<?php echo URL.'/img/favicon.png';?>" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url($_SERVER['app.baseURL'].'/img/favicon.png');?>" type="image/x-icon" />

  <title>BRAPCI</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?php echo URL.'assets/css/nucleo-icons.css'; ?>" rel="stylesheet" />
  <link href="<?php echo URL.'assets/css/nucleo-svg.css'; ?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo base_url($_SERVER['app.baseURL'].'assets/css/soft-ui-dashboard.css?v=1.0.4d');?>" rel="stylesheet" />
</head>
<?php require("_css_fonts.php");?>

<body class="g-sidenav-show  bg-gray-100">
<?php		
if (get("debug") != '')
			{
				$tela .= '<style> div { border: 1px solid #000; }</style>';
			}
?>