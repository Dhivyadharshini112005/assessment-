<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
require_employee();

$emp_id = $_SESSION['employee_id'];

$check = $pdo->prepare("SELECT * FROM stage1_attempts WHERE employee_id=? AND status='PASSED' ORDER BY id DESC LIMIT 1");
$check->execute([$emp_id]);
if (!$check->fetch()) {
    header("Location: dashboard.php");
    exit;
}

$video = $pdo->query("SELECT * FROM training_videos WHERE stage=2 AND active=1 ORDER BY id DESC LIMIT 1")->fetch();
if (empty($_SESSION['stage2_question_ids'])) {
    $bank = $pdo->query("SELECT id FROM stage2_questions WHERE active=1 ORDER BY RAND() LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
    if (count($bank) < 5) {
        $questions = [];
    } else {
        $_SESSION['stage2_question_ids'] = array_map('intval', $bank);
    }
}
$ids = array_map('intval', $_SESSION['stage2_question_ids'] ?? []);
$questions = [];
if (count($ids) === 5) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmtQ = $pdo->prepare("SELECT * FROM stage2_questions WHERE active=1 AND id IN ($placeholders)");
    $stmtQ->execute($ids);
    $found = [];
    foreach ($stmtQ->fetchAll() as $q) $found[(int)$q['id']] = $q;
    foreach ($ids as $id) if (isset($found[$id])) $questions[] = $found[$id];
}

// The employee must finish the currently active Stage 2 video before questions are unlocked.
$video_completed = false;
if ($video) {
    $video_completed = !empty($_SESSION['stage2_video_completed_id'])
        && (int)$_SESSION['stage2_video_completed_id'] === (int)$video['id'];
}

// Mark the video as completed only after the browser reports that playback reached the end.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'video_complete') {
    header('Content-Type: application/json; charset=utf-8');
    if ($video) {
        $_SESSION['stage2_video_completed_id'] = (int)$video['id'];
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Server-side protection: answers cannot be submitted before the video is completed.
    if (!$video_completed) {
        header("Location: stage2.php");
        exit;
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO stage2_answers(employee_id,question_id,answer) VALUES(?,?,?)");
        foreach ($questions as $q) {
            $stmt->execute([$emp_id, $q['id'], trim($_POST['answer'][$q['id']] ?? '')]);
        }

        $existing = $pdo->prepare("SELECT id FROM stage2_results WHERE employee_id=? AND status='PENDING' LIMIT 1");
        $existing->execute([$emp_id]);
        if (!$existing->fetch()) {
            $pdo->prepare("INSERT INTO stage2_results(employee_id,status) VALUES(?, 'PENDING')")->execute([$emp_id]);
        }

        $pdo->commit();
        unset($_SESSION['stage2_question_ids']);
        header("Location: dashboard.php");
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

$base_path = "../";
include "../includes/header.php";
?>

<div class="card">
    <h2>Stage 2 – Practical Telecaller Assessment</h2>
    <p>Watch the complete advanced training video. The practical questions will unlock automatically when the video finishes.</p>

    <?php if ($video && $video['video_path']): ?>
        <video id="stage2Video" class="video" controls preload="metadata">
            <source src="../<?=htmlspecialchars($video['video_path'])?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div id="videoStatus" class="alert <?= $video_completed ? 'ok' : '' ?>" style="margin-top:14px;">
            <?php if ($video_completed): ?>
                ✅ Video completed. You can now answer the practical questions below.
            <?php else: ?>
                ▶️ Please watch the video until it reaches the end. Questions will unlock after completion.
            <?php endif; ?>
        </div>

        <div id="continueWrap" class="center" style="margin-top:16px;<?= $video_completed ? '' : 'display:none;' ?>">
            <button type="button" id="continueToQuestions" class="btn success">Continue to Questions →</button>
        </div>
    <?php else: ?>
        <div class="alert bad">Stage 2 training video is not configured yet.</div>
    <?php endif; ?>
</div>

<div id="questionsCard" class="card" style="<?= $video_completed ? '' : 'display:none;' ?>">
    <h2>Practical Questions</h2>
    <p class="muted">5 questions are randomly selected from the Stage 2 question bank. Answer each question briefly and clearly. Your answers will be reviewed manually by the admin.</p>

    <form method="post" id="stage2Form">
        <?php foreach ($questions as $i => $q): ?>
            <div class="question">
                <h3><?=($i+1)?>. <?=htmlspecialchars($q['question'])?></h3>
                <textarea name="answer[<?=$q['id']?>]" required placeholder="Type your answer..."></textarea>
            </div>
        <?php endforeach; ?>
        <button class="btn success" type="submit">Submit Stage 2</button>
    </form>
</div>

<?php if ($video && $video['video_path'] && !$video_completed): ?>
<script>
(function () {
    const video = document.getElementById('stage2Video');
    const status = document.getElementById('videoStatus');
    const continueWrap = document.getElementById('continueWrap');
    const questionsCard = document.getElementById('questionsCard');
    const continueButton = document.getElementById('continueToQuestions');

    if (!video) return;

    video.addEventListener('ended', async function () {
        try {
            const response = await fetch('stage2.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                body: 'action=video_complete'
            });
            const data = await response.json();

            if (data.success) {
                status.className = 'alert ok';
                status.textContent = '✅ Video completed. You can now answer the practical questions.';
                continueWrap.style.display = 'block';
            } else {
                status.className = 'alert bad';
                status.textContent = 'Unable to unlock the questions. Please reload the page and watch the video again.';
            }
        } catch (error) {
            status.className = 'alert bad';
            status.textContent = 'Unable to save video completion. Please check your connection and watch the video again.';
        }
    });

    continueButton.addEventListener('click', function () {
        questionsCard.style.display = 'block';
        continueWrap.style.display = 'none';
        questionsCard.scrollIntoView({behavior: 'smooth', block: 'start'});
    });
})();
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>
