<?php
require_once "../config/database.php";require_once "../includes/auth.php";require_admin();
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM employees WHERE id=?");$s->execute([$id]);$e=$s->fetch();if(!$e)die("Employee not found");
$s1=$pdo->prepare("SELECT * FROM stage1_attempts WHERE employee_id=? ORDER BY id DESC");$s1->execute([$id]);$a1=$s1->fetchAll();
$s2=$pdo->prepare("SELECT * FROM stage2_results WHERE employee_id=? ORDER BY id DESC");$s2->execute([$id]);$a2=$s2->fetchAll();
$base_path="../";include "../includes/header.php";?>
<div class="card"><h2><?=htmlspecialchars($e['name'])?></h2><p><?=htmlspecialchars($e['email'])?></p><p>Phone: <?=htmlspecialchars($e['phone'])?></p></div>
<div class="card"><h3>Stage 1 Attempts</h3><table class="table"><tr><th>Date</th><th>Score</th><th>%</th><th>Status</th></tr><?php foreach($a1 as $x):?><tr><td><?=$x['created_at']?></td><td><?=$x['score']?>/<?=$x['total']?></td><td><?=$x['percentage']?>%</td><td><?=$x['status']?></td></tr><?php endforeach;?></table></div>
<div class="card"><h3>Stage 2 Results</h3><table class="table"><tr><th>Date</th><th>Status</th></tr><?php foreach($a2 as $x):?><tr><td><?=$x['created_at']?></td><td><?=$x['status']?></td></tr><?php endforeach;?></table></div>
<?php include "../includes/footer.php";?>