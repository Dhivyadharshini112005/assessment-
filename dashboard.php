<?php
require_once "../config/database.php"; require_once "../includes/auth.php"; require_employee();
$emp_id=$_SESSION['employee_id'];
$stmt=$pdo->prepare("SELECT * FROM employees WHERE id=?"); $stmt->execute([$emp_id]); $emp=$stmt->fetch();
$s1=$pdo->prepare("SELECT * FROM stage1_attempts WHERE employee_id=? ORDER BY id DESC LIMIT 1"); $s1->execute([$emp_id]); $a1=$s1->fetch();
$s2=$pdo->prepare("SELECT * FROM stage2_results WHERE employee_id=? ORDER BY id DESC LIMIT 1"); $s2->execute([$emp_id]); $a2=$s2->fetch();
$base_path="../"; include "../includes/header.php";
?>
<div class="card">
<h2>Welcome, <?=htmlspecialchars($emp['name'])?></h2>
<p>F-Taxi Telecaller Assessment</p>
</div>
<div class="grid">
<div class="card"><h3>Stage 1</h3>
<p>Complete the 10-question MCQ test.</p>
<?php if($a1): ?>
<p>Latest Score: <strong><?=htmlspecialchars($a1['percentage'])?>%</strong></p>
<p>Status: <span class="pill"><?=htmlspecialchars($a1['status'])?></span></p>
<?php endif; ?>
<a class="btn" href="stage1.php">Open Stage 1</a>
</div>
<div class="card"><h3>Stage 2</h3>
<?php if(!$a1 || $a1['status']!=='PASSED'): ?>
<p class="muted">Stage 2 is locked until Stage 1 is passed.</p>
<?php else: ?>
<p>Watch the advanced training video and answer practical questions manually.</p>
<a class="btn success" href="stage2.php">Open Stage 2</a>
<?php endif; ?>
<?php if($a2): ?><p>Evaluation: <strong><?=htmlspecialchars($a2['status'])?></strong></p><?php endif; ?>
</div>
</div>
<?php if($a2 && $a2['status']==='PASSED'): ?>
<div class="card center"><h2>🎉 Assessment Completed</h2><p>You have passed both stages.</p><a class="btn success" href="offer_letter.php">View Offer Letter</a></div>
<?php endif; ?>
<?php include "../includes/footer.php"; ?>