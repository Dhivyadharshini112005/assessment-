<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
require_admin();

$msg = "";
$msg_type = "ok";

if (isset($_POST['stage1'])) {
    $s = $pdo->prepare("INSERT INTO stage1_questions(question,option_a,option_b,option_c,option_d,correct_option,active) VALUES(?,?,?,?,?,?,1)");
    $s->execute([$_POST['question'], $_POST['a'], $_POST['b'], $_POST['c'], $_POST['d'], $_POST['correct']]);
    $msg = "Stage 1 question added. The question bank can contain up to 20 Stage 1 questions. Employees receive 10 random questions.";
}

if (isset($_POST['stage2'])) {
    $s = $pdo->prepare("INSERT INTO stage2_questions(question) VALUES(?)");
    $s->execute([$_POST['question']]);
    $msg = "Stage 2 question added.";
}

if (isset($_POST['edit_stage1'])) {
    $id = (int)$_POST['edit_stage1'];
    $s = $pdo->prepare("UPDATE stage1_questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=? WHERE id=?");
    $s->execute([trim($_POST['question']), trim($_POST['a']), trim($_POST['b']), trim($_POST['c']), trim($_POST['d']), $_POST['correct'], $id]);
    $msg = "Stage 1 question updated successfully.";
}

if (isset($_POST['edit_stage2'])) {
    $id = (int)$_POST['edit_stage2'];
    $s = $pdo->prepare("UPDATE stage2_questions SET question=? WHERE id=?");
    $s->execute([trim($_POST['question']), $id]);
    $msg = "Stage 2 question updated successfully.";
}

if (isset($_POST['delete_stage1'])) {
    $id = (int)$_POST['delete_stage1'];
    $s = $pdo->prepare("UPDATE stage1_questions SET active=0 WHERE id=?");
    $s->execute([$id]);
    $msg = "Stage 1 question deleted successfully. The question ID and record are preserved.";
}

if (isset($_POST['delete_stage2'])) {
    $id = (int)$_POST['delete_stage2'];
    $s = $pdo->prepare("UPDATE stage2_questions SET active=0 WHERE id=?");
    $s->execute([$id]);
    $msg = "Stage 2 question deleted successfully. The question ID and record are preserved.";
}



$s1 = $pdo->query("SELECT * FROM stage1_questions ORDER BY active DESC, id DESC")->fetchAll();
$s2 = $pdo->query("SELECT * FROM stage2_questions ORDER BY id DESC")->fetchAll();
$active_s1_count = (int)$pdo->query("SELECT COUNT(*) FROM stage1_questions WHERE active=1")->fetchColumn();

$base_path = "../";
include "../includes/header.php";
?>

<?php if ($msg): ?>
    <div class="alert <?=$msg_type === 'bad' ? 'bad' : 'ok'?>"><?=htmlspecialchars($msg)?></div>
<?php endif; ?>

<div class="grid">
    <div class="card">
        <h2>Add Stage 1 MCQ</h2>
        <p><strong>Active questions: <?=$active_s1_count?></strong></p>
        <p class="muted">Keep your Stage 1 question bank at 20 questions. Employees receive 10 random questions. Existing questions can be edited without deleting them.</p>
        <form method="post">
            <input type="hidden" name="stage1" value="1">
            <label>Question</label><textarea name="question" required></textarea>
            <label>Option A</label><input name="a" required>
            <label>Option B</label><input name="b" required>
            <label>Option C</label><input name="c" required>
            <label>Option D</label><input name="d" required>
            <label>Correct Option</label>
            <select name="correct"><option>A</option><option>B</option><option>C</option><option>D</option></select>
            <button class="btn">Add Question</button>
        </form>
    </div>

    <div class="card">
        <h2>Add Stage 2 Question</h2>
        <form method="post">
            <input type="hidden" name="stage2" value="1">
            <label>Question</label><textarea name="question" required></textarea>
            <button class="btn">Add Question</button>
        </form>
    </div>
</div>

<div class="card">
    <h2>Stage 1 Questions</h2>
    <p class="muted">You can edit existing questions directly. Editing does not delete the question or change its ID.</p>
    <table class="table">
        <tr><th>ID</th><th>Question</th><th>Correct</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($s1 as $q): ?>
            <tr>
                <td><?=$q['id']?></td>
                <td><?=htmlspecialchars($q['question'])?></td>
                <td><?=$q['correct_option']?></td>
                <td><?=($q['active'] ? 'Active' : 'Deleted')?></td>
                <td>
                    <?php if ($q['active']): ?>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <details>
                                <summary class="btn" style="display:inline-block;cursor:pointer;list-style:none;">Edit</summary>
                                <form method="post" class="card" style="margin-top:10px;text-align:left;min-width:320px;">
                                    <input type="hidden" name="edit_stage1" value="<?=$q['id']?>">
                                    <label>Question</label><textarea name="question" required><?=htmlspecialchars($q['question'])?></textarea>
                                    <label>Option A</label><input name="a" value="<?=htmlspecialchars($q['option_a'])?>" required>
                                    <label>Option B</label><input name="b" value="<?=htmlspecialchars($q['option_b'])?>" required>
                                    <label>Option C</label><input name="c" value="<?=htmlspecialchars($q['option_c'])?>" required>
                                    <label>Option D</label><input name="d" value="<?=htmlspecialchars($q['option_d'])?>" required>
                                    <label>Correct Option</label>
                                    <select name="correct">
                                        <?php foreach (['A','B','C','D'] as $op): ?>
                                            <option value="<?=$op?>" <?=($q['correct_option']===$op?'selected':'')?>><?=$op?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn success" type="submit">Save Changes</button>
                                </form>
                            </details>
                            <form method="post" style="display:inline;margin:0;" onsubmit="return confirm('Delete this Stage 1 question? It will be deactivated, not permanently removed.');">
                                <input type="hidden" name="delete_stage1" value="<?=$q['id']?>">
                                <button class="btn danger" type="submit">Delete</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <span class="muted">Deleted</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card">
    <h2>Stage 2 Questions</h2>
    <p class="muted">Add new practical questions anytime. Existing questions can be edited without deleting them.</p>
    <table class="table">
        <tr><th>ID</th><th>Question</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($s2 as $q): ?>
            <tr>
                <td><?=$q['id']?></td>
                <td><?=htmlspecialchars($q['question'])?></td>
                <td><?=($q['active'] ? 'Active' : 'Deleted')?></td>
                <td>
                    <?php if ($q['active']): ?>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <details>
                                <summary class="btn" style="display:inline-block;cursor:pointer;list-style:none;">Edit</summary>
                                <form method="post" class="card" style="margin-top:10px;text-align:left;min-width:320px;">
                                    <input type="hidden" name="edit_stage2" value="<?=$q['id']?>">
                                    <label>Question</label><textarea name="question" required><?=htmlspecialchars($q['question'])?></textarea>
                                    <button class="btn success" type="submit">Save Changes</button>
                                </form>
                            </details>
                            <form method="post" style="display:inline;margin:0;" onsubmit="return confirm('Delete this Stage 2 question? It will be deactivated, not permanently removed.');">
                                <input type="hidden" name="delete_stage2" value="<?=$q['id']?>">
                                <button class="btn danger" type="submit">Delete</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <span class="muted">Deleted</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include "../includes/footer.php"; ?>
