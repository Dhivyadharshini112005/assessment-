<?php
require_once "../config/database.php";
require_once "../includes/auth.php";
require_employee();

$emp_id = $_SESSION['employee_id'];
$error = "";
$result = null;

// Stage 1 has a 20-question bank. Each employee gets 10 random questions.
// The selected 10 are stored in the session so refresh does not change the attempt.
if (empty($_SESSION['stage1_question_ids'])) {
    $bank = $pdo->query("SELECT id FROM stage1_questions WHERE active=1 ORDER BY RAND() LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
    if (count($bank) < 10) {
        $error = "Stage 1 is not ready. The admin must keep at least 10 active MCQ questions.";
        $questions = [];
    } else {
        $_SESSION['stage1_question_ids'] = array_map('intval', $bank);
    }
}

if (!$error) {
    $ids = array_map('intval', $_SESSION['stage1_question_ids'] ?? []);
    if (count($ids) !== 10) {
        $error = "Stage 1 question selection is invalid. Please start the test again.";
        $questions = [];
    } else {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM stage1_questions WHERE active=1 AND id IN ($placeholders)");
        $stmt->execute($ids);
        $questions = $stmt->fetchAll();
        $byId = [];
        foreach ($questions as $q) $byId[(int)$q['id']] = $q;
        $questions = [];
        foreach ($ids as $id) if (isset($byId[$id])) $questions[] = $byId[$id];
        if (count($questions) !== 10) $error = "Some selected questions are no longer active. Please start the test again.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $answers = $_POST['answer'] ?? [];
    $submitted_ids = array_map('intval', array_keys($answers));
    $question_ids = array_map('intval', array_column($questions, 'id'));
    sort($submitted_ids);
    sort($question_ids);

    if ($submitted_ids !== $question_ids) {
        $error = "Please answer all 10 questions before submitting.";
    } else {
        $correct = 0;
        foreach ($questions as $q) {
            if (isset($answers[$q['id']]) && $answers[$q['id']] === $q['correct_option']) $correct++;
        }
        $total = 10;
        $percentage = round(($correct / $total) * 100, 2);
        $status = $percentage >= 60 ? 'PASSED' : 'FAILED';
        $pdo->prepare("INSERT INTO stage1_attempts(employee_id,score,total,percentage,status) VALUES(?,?,?,?,?)")
            ->execute([$emp_id, $correct, $total, $percentage, $status]);
        $result = [$correct, $total, $percentage, $status];
        unset($_SESSION['stage1_question_ids']);
    }
}

// Shuffle the selected 10 for display only; the selected set remains fixed during the attempt.
if (!$error && !$result && count($questions) === 10) shuffle($questions);

$base_path = "../";

// Display bilingual text cleanly: English first, Tamil on the next line.
function bilingual_text($text) {
    $text = trim((string)$text);
    $text = preg_replace('/^[A-D]\.\s*/u', '', $text);

    $hasTamil = function($s) {
        return preg_match('/[\x{0B80}-\x{0BFF}]/u', $s) === 1;
    };

    // Preferred format: either "English / Tamil" or "Tamil / English".
    if (strpos($text, ' / ') !== false) {
        [$left, $right] = array_pad(explode(' / ', $text, 2), 2, '');
        $left = trim($left); $right = trim($right);
        if ($hasTamil($left) && !$hasTamil($right)) {
            [$english, $tamil] = [$right, $left];
        } elseif (!$hasTamil($left) && $hasTamil($right)) {
            [$english, $tamil] = [$left, $right];
        } else {
            [$english, $tamil] = [$left, $right];
        }
        return '<span class="bilingual-en">'.htmlspecialchars($english).'</span>'
             . '<span class="bilingual-ta">'.htmlspecialchars($tamil).'</span>';
    }

    // If a question contains a complete English sentence followed by Tamil,
    // split after the first question mark.
    if (preg_match('/^(.*?\?)(\s*)(.+)$/us', $text, $m) && $hasTamil($m[3])) {
        return '<span class="bilingual-en">'.htmlspecialchars(trim($m[1])).'</span>'
             . '<span class="bilingual-ta">'.htmlspecialchars(trim($m[3])).'</span>';
    }

    // Options commonly use: "English sentence. Tamil translation".
    // Split after the first sentence-ending period so English tariff terms
    // such as KM / waiting charge can remain naturally inside the Tamil line.
    if ($hasTamil($text) && preg_match('/^(.+?\.)(\s+)(.+)$/us', $text, $m) && $hasTamil($m[3])) {
        return '<span class="bilingual-en">'.htmlspecialchars(trim($m[1])).'</span>'
             . '<span class="bilingual-ta">'.htmlspecialchars(trim($m[3])).'</span>';
    }

    // Fallback for mixed text without punctuation: split before the first Tamil
    // word. This keeps the question readable even for older records.
    if ($hasTamil($text) && preg_match('/\s+(?=[\x{0B80}-\x{0BFF}])/u', $text, $m, PREG_OFFSET_CAPTURE)) {
        $pos = $m[0][1];
        $english = trim(substr($text, 0, $pos));
        $tamil = trim(substr($text, $pos));
        if ($english !== '' && $tamil !== '') {
            return '<span class="bilingual-en">'.htmlspecialchars($english).'</span>'
                 . '<span class="bilingual-ta">'.htmlspecialchars($tamil).'</span>';
        }
    }

    return '<span class="bilingual-en">'.htmlspecialchars($text).'</span>';
}

include "../includes/header.php";
?>

<div class="card">
    <h2>Stage 1 – MCQ Test</h2>
    <p>Complete all <strong>10 MCQ questions</strong>. Passing score: <strong>60%</strong>.</p>

    <?php if ($result): ?>
        <div class="alert <?=($result[3] === 'PASSED' ? 'ok' : 'bad')?>">
            Score: <?=$result[0]?>/<?=$result[1]?> (<?=$result[2]?>%).
            <strong><?=$result[3]?></strong>
        </div>

        <?php if ($result[3] === 'PASSED'): ?>
            <div class="card" style="margin-top:16px; text-align:center;">
                <h3>🎉 Stage 1 Passed!</h3>
                <p>You can now continue to Stage 2 – Advanced Telecaller Practical Assessment.</p>
                <a class="btn success" href="stage2.php">Go to Stage 2 →</a>
            </div>
        <?php else: ?>
            <div style="margin-top:16px;">
                <a class="btn" href="dashboard.php">Back to Dashboard</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert bad"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

</div>

<?php if (!$error && !$result && count($questions) === 10): ?>
<div class="card">
    <h2>Stage 1 MCQ Test</h2>
    <p class="muted">There are 20 questions in the question bank. 10 questions are randomly selected for each employee.</p>
    <form method="post">
        <?php foreach ($questions as $i => $q): ?>
            <div class="question mcq-question">
                <h3><?=($i + 1)?>. <?=bilingual_text($q['question'])?></h3>
                <label class="mcq-option" style="display:flex!important;align-items:center!important;justify-content:flex-start!important;flex-direction:row!important;gap:10px!important;text-align:left!important;">
                    <input type="radio" name="answer[<?=$q['id']?>]" value="A" required style="order:1!important;flex:0 0 18px!important;width:18px!important;height:18px!important;margin:0!important;">
                    <span class="mcq-option-text" style="order:2!important;display:block!important;flex:1!important;margin:0!important;padding:0!important;text-align:left!important;">A. <?=bilingual_text($q['option_a'])?></span>
                </label>
                <label class="mcq-option" style="display:flex!important;align-items:center!important;justify-content:flex-start!important;flex-direction:row!important;gap:10px!important;text-align:left!important;">
                    <input type="radio" name="answer[<?=$q['id']?>]" value="B" style="order:1!important;flex:0 0 18px!important;width:18px!important;height:18px!important;margin:0!important;">
                    <span class="mcq-option-text" style="order:2!important;display:block!important;flex:1!important;margin:0!important;padding:0!important;text-align:left!important;">B. <?=bilingual_text($q['option_b'])?></span>
                </label>
                <label class="mcq-option" style="display:flex!important;align-items:center!important;justify-content:flex-start!important;flex-direction:row!important;gap:10px!important;text-align:left!important;">
                    <input type="radio" name="answer[<?=$q['id']?>]" value="C" style="order:1!important;flex:0 0 18px!important;width:18px!important;height:18px!important;margin:0!important;">
                    <span class="mcq-option-text" style="order:2!important;display:block!important;flex:1!important;margin:0!important;padding:0!important;text-align:left!important;">C. <?=bilingual_text($q['option_c'])?></span>
                </label>
                <label class="mcq-option" style="display:flex!important;align-items:center!important;justify-content:flex-start!important;flex-direction:row!important;gap:10px!important;text-align:left!important;">
                    <input type="radio" name="answer[<?=$q['id']?>]" value="D" style="order:1!important;flex:0 0 18px!important;width:18px!important;height:18px!important;margin:0!important;">
                    <span class="mcq-option-text" style="order:2!important;display:block!important;flex:1!important;margin:0!important;padding:0!important;text-align:left!important;">D. <?=bilingual_text($q['option_d'])?></span>
                </label>
            </div>
        <?php endforeach; ?>
        <button class="btn" type="submit">Submit Stage 1</button>
    </form>
</div>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>
