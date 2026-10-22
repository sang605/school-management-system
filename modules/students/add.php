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

$name = '';
$grade = '';
$email = '';
$errors = [];
$submitted = false;
$allowedGrades = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6',
    'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $grade = $_POST['grade'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if ($name === '') {
        $errors[] = 'Enter the student’s name.';
    }
    if (!in_array($grade, $allowedGrades, true)) {
        $errors[] = 'Choose a valid grade.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    $submitted = count($errors) === 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add student</title>
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
        <h1>Add a student</h1>
        <p><a href="view.php">Back to students</a> | <a href="../../dashboard.php">Dashboard</a></p>
    </header>

    <p class="notice">This example validates the form but does not save records. Database storage is not configured yet.</p>

    <main>
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

        <?php if ($submitted): ?>
            <section class="success" role="status">
                <h2>Form details are valid</h2>
                <p>This student has not been saved because database storage is not set up.</p>
                <p><strong>Name:</strong> <?= escape($name) ?></p>
                <p><strong>Grade:</strong> <?= escape($grade) ?></p>
                <p><strong>Email:</strong> <?= escape($email) ?></p>
            </section>
        <?php endif; ?>

        <form method="post" action="">
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

            <button type="submit">Validate student details</button>
        </form>
    </main>
</body>
</html>