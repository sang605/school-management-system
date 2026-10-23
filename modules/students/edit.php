<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/login.php');
    exit();
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$students = [
    1 => ['name' => 'Aarav Sharma', 'grade' => 'Grade 8', 'email' => 'aarav@example.com'],
    2 => ['name' => 'Maya Rai', 'grade' => 'Grade 7', 'email' => 'maya@example.com'],
    3 => ['name' => 'Samir Thapa', 'grade' => 'Grade 9', 'email' => 'samir@example.com'],
];
$allowedGrades = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6',
    'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$errors = [];
$updated = false;
$student = is_int($id) && isset($students[$id]) ? $students[$id] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student !== null) {
    $name = isset($_POST['name']) && is_string($_POST['name']) ? trim($_POST['name']) : '';
    $grade = isset($_POST['grade']) && is_string($_POST['grade']) ? $_POST['grade'] : '';
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';

    if ($name === '') {
        $errors[] = 'Enter the student’s name.';
    }
    if (!in_array($grade, $allowedGrades, true)) {
        $errors[] = 'Choose a valid grade.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($errors === []) {
        $student = ['name' => $name, 'grade' => $grade, 'email' => $email];
        $updated = true;
    }
} elseif ($student !== null) {
    $name = $student['name'];
    $grade = $student['grade'];
    $email = $student['email'];
} else {
    http_response_code(404);
    $name = '';
    $grade = '';
    $email = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem auto; max-width: 640px; padding: 0 1rem; }
        label { display: block; margin-top: 1rem; }
        input, select { box-sizing: border-box; margin-top: .35rem; max-width: 100%; padding: .6rem; width: 100%; }
        button { cursor: pointer; margin-top: 1rem; padding: .65rem 1rem; }
        .notice { background: #fff4d6; padding: .75rem; }
        .error { color: #a00000; }
        .success { background: #eaf7ea; padding: .75rem; }
    </style>
</head>
<body>
    <header>
        <h1>Edit student</h1>
        <p><a href="view.php">Back to students</a> | <a href="../../dashboard.php">Dashboard</a></p>
    </header>

    <main>
        <?php if ($student === null): ?>
            <h2>Student not found</h2>
            <p>Choose a student from the <a href="view.php">student list</a>.</p>
        <?php else: ?>
            <p class="notice">This is a sample edit form. Changes are validated and previewed but are not saved.</p>

            <?php if ($errors !== []): ?>
                <div class="error" role="alert">
                    <p>Please correct the following:</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($updated): ?>
                <section class="success" role="status">
                    <h2>Updated details are valid</h2>
                    <p>These changes are only a preview; the student record has not been saved.</p>
                </section>
            <?php endif; ?>

            <form method="post" action="?id=<?= (int) $id ?>">
                <label for="name">Student name</label>
                <input type="text" id="name" name="name" value="<?= escape($name) ?>" maxlength="100" required>

                <label for="grade">Grade</label>
                <select id="grade" name="grade" required>
                    <option value="">Select a grade</option>
                    <?php foreach ($allowedGrades as $allowedGrade): ?>
                        <option value="<?= escape($allowedGrade) ?>" <?= $grade === $allowedGrade ? 'selected' : '' ?>>
                            <?= escape($allowedGrade) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="<?= escape($email) ?>" maxlength="254" required>

                <button type="submit">Validate changes</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>