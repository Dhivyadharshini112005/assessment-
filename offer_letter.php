<?php
require_once "../config/database.php"; require_once "../includes/auth.php"; require_employee();
$emp_id=$_SESSION['employee_id'];
$stmt=$pdo->prepare("SELECT e.*, s1.status AS s1status, s2.status AS s2status FROM employees e LEFT JOIN (SELECT employee_id,status FROM stage1_attempts ORDER BY id DESC) s1 ON s1.employee_id=e.id LEFT JOIN stage2_results s2 ON s2.employee_id=e.id WHERE e.id=? ORDER BY s2.id DESC LIMIT 1");
$stmt->execute([$emp_id]); $emp=$stmt->fetch();
if(!$emp || $emp['s1status']!=='PASSED' || $emp['s2status']!=='PASSED'){header("Location: dashboard.php");exit;}
$base_path="../"; include "../includes/header.php";
?>
<div class="card offer-card" id="offer">
<div class="offer-logo-wrap"><img src="../assets/images/ftaxi-logo.webp" alt="F-Taxi" class="offer-logo"></div>
<h1 class="center">F-TAXI</h1><h2 class="center">OFFER LETTER</h2>
<p>Date: <?=date('d-m-Y')?></p>
<p>Dear <strong><?=htmlspecialchars($emp['name'])?></strong>,</p>
<p>We are pleased to offer you the position of <strong>Telecaller</strong> with F-Taxi, subject to the applicable company terms and conditions.</p>
<p>You have successfully completed both stages of the F-Taxi Telecaller Assessment.</p>
<p>We look forward to having you as part of the F-Taxi team.</p>
<br><p>For F-Taxi</p><p><strong>Authorized Signatory</strong></p>
</div>
<div class="center"><button class="btn" onclick="window.print()">Print / Save as PDF</button></div>
<?php include "../includes/footer.php"; ?>