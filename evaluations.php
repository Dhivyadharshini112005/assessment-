<?php
require_once "../config/database.php";require_once "../includes/auth.php";require_admin();
if(isset($_POST['result_id'])){
 $status=$_POST['status'];$pdo->prepare("UPDATE stage2_results SET status=?,evaluated_by=?,evaluated_at=NOW() WHERE id=?")->execute([$status,$_SESSION['admin_id'],$_POST['result_id']]);
}
$rows=$pdo->query("SELECT r.*,e.name,e.email FROM stage2_results r JOIN employees e ON e.id=r.employee_id ORDER BY r.id DESC")->fetchAll();
$base_path="../";include "../includes/header.php";?>
<div class="card"><h2>Stage 2 Evaluation</h2>
<?php if(!$rows):?><p>No submissions yet.</p><?php endif;?>
<?php foreach($rows as $r):?>
<div class="card"><h3><?=htmlspecialchars($r['name'])?> <span class="pill"><?=$r['status']?></span></h3><p><?=htmlspecialchars($r['email'])?></p>
<?php $qs=$pdo->prepare("SELECT q.question,a.answer FROM stage2_answers a JOIN stage2_questions q ON q.id=a.question_id WHERE a.employee_id=? ORDER BY a.id");$qs->execute([$r['employee_id']]);foreach($qs as $x):?>
<p><strong>Question:</strong> <?=htmlspecialchars($x['question'])?></p><div class="answer-box"><?=htmlspecialchars($x['answer'])?></div><hr>
<?php endforeach;?>
<form method="post"><input type="hidden" name="result_id" value="<?=$r['id']?>"><button name="status" value="PASSED" class="btn success">Mark Passed</button> <button name="status" value="FAILED" class="btn danger">Mark Failed</button></form>
</div>
<?php endforeach;?></div><?php include "../includes/footer.php";?>