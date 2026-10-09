<?php
require_once "../config/database.php";require_once "../includes/auth.php";require_admin();
$msg="";
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['video'])){
 $stage=(int)$_POST['stage'];$name=basename($_FILES['video']['name']);$ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
 if($ext!=='mp4'){$msg="Only MP4 video is supported.";}else{
  $new="stage".$stage."_".time().".mp4";$target="../uploads/videos/".$new;
  if(move_uploaded_file($_FILES['video']['tmp_name'],$target)){
   $pdo->prepare("UPDATE training_videos SET active=0 WHERE stage=?")->execute([$stage]);
   $pdo->prepare("INSERT INTO training_videos(stage,title,video_path,active) VALUES(?,?,?,1)")->execute([$stage,"Stage $stage Training","uploads/videos/".$new]);
   $msg="Stage $stage video uploaded and activated.";
  }else $msg="Upload failed.";
 }
}
$videos=$pdo->query("SELECT * FROM training_videos ORDER BY id DESC")->fetchAll();
$base_path="../";include "../includes/header.php";?>
<div class="card"><h2>Training Videos</h2><form method="post" enctype="multipart/form-data">
<input type="hidden" name="stage" value="2"><label>Stage</label><select disabled><option value="2">Stage 2 – Advanced</option></select>
<label>MP4 Video</label><input type="file" name="video" accept="video/mp4" required><button class="btn">Upload & Activate</button></form>
<?php if($msg):?><div class="alert ok"><?=$msg?></div><?php endif;?></div>
<div class="card"><h3>Uploaded Videos</h3><table class="table"><tr><th>Stage</th><th>Title</th><th>Status</th></tr><?php foreach($videos as $v):?><tr><td><?=$v['stage']?></td><td><?=htmlspecialchars($v['title'])?></td><td><?=$v['active']?'Active':'Inactive'?></td></tr><?php endforeach;?></table></div>
<?php include "../includes/footer.php";?>