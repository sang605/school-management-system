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
        :root {
            color-scheme: light;
            font-family: Arial, sans-serif;
            color: #172033;
            background: #f3f6fb;
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; }
        .page { margin: 0 auto; max-width: 760px; padding: 2rem 1rem 4rem; }
        .topbar, .topbar nav { align-items: center; display: flex; gap: 1rem; justify-content: space-between; }
        .topbar { margin-bottom: 1.5rem; }
        .topbar h1 { font-size: 1.5rem; margin: 0; }
        a { color: #2457c5; }
        .topbar nav { flex-wrap: wrap; }
        .card { background: #fff; border: 1px solid #e1e7f0; border-radius: 12px; box-shadow: 0 8px 24px #1720330d; padding: 1.5rem; }
        .intro { margin-bottom: 1.5rem; }
        .intro h2 { margin: 0 0 .5rem; }
        .intro p { color: #536078; line-height: 1.5; margin: 0; }
        .notice, .error, .success { border-radius: 8px; margin: 1rem 0; padding: .9rem 1rem; }
        .notice { background: #fff7df; border: 1px solid #f0d98f; color: #624b11; }
        .error { background: #fff0ef; border: 1px solid #f1c2bd; color: #8b2118; }
        .success { background: #eff9f0; border: 1px solid #b9dfbd; color: #205b2a; }
        .error p { margin-top: 0; }
        .error ul { margin-bottom: 0; }
        .fields { display: grid; gap: 1rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .field { min-width: 0; }
        .field-email { grid-column: 1 / -1; }
        label { display: block; font-size: .95rem; font-weight: 600; margin-bottom: .4rem; }
        input, select {
            background: #fff;
            border: 1px solid #bdc8d8;
            border-radius: 7px;
            color: inherit;
            font: inherit;
            min-height: 44px;
            padding: .65rem .75rem;
            width: 100%;
        }
        input:focus, select:focus { border-color: #2457c5; outline: 3px solid #2457c533; }
        .actions { border-top: 1px solid #e7ebf2; margin-top: 1.5rem; padding-top: 1.25rem; }
        button { background: #2457c5; border: 0; border-radius: 7px; color: #fff; cursor: pointer; font: inherit; font-weight: 600; min-height: 44px; padding: .65rem 1rem; }
        button:hover { background: #1947aa; }
        button:focus-visible, a:focus-visible { outline: 3px solid #2457c566; outline-offset: 3px; }
        @media (max-width: 560px) {
            .page { padding-top: 1.25rem; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .card { padding: 1.1rem; }
            .fields { grid-template-columns: 1fr; }
            .field-email { grid-column: auto; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="topbar">
            <h1>School Management</h1>
            <nav aria-label="Page navigation">
                <a href="view.php">Students</a>
                <a href="../../dashboard.php">Dashboard</a>
            </nav>
        </header>

        <main class="card">
            <div class="intro">
                <h2>Add a student</h2>
                <p>Enter the student’s details below. Fields marked with an asterisk are required.</p>
            </div>

            <p class="notice">This form checks the details and shows a preview. It does not save student records yet.</p>

        <?php if ($errors !== []): ?>
            <div class="error" role="alert" aria-live="assertive">
                <p>Please correct the following:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($submitted): ?>
            <section class="success" role="status" aria-live="polite">
                <h2>Form details are valid</h2>
                <p>This student has not been saved because database storage is not set up.</p>
                <p><strong>Name:</strong> <?= escape($name) ?></p>
                <p><strong>Grade:</strong> <?= escape($grade) ?></p>
                <p><strong>Email:</strong> <?= escape($email) ?></p>
            </section>
        <?php endif; ?>

        <form method="post" action="">
            <div class="fields">
                <div class="field">
                    <label for="name">Student name *</label>
                    <input type="text" id="name" name="name" value="<?= escape($name) ?>" maxlength="100" autocomplete="name" required>
                </div>

                <div class="field">
                    <label for="grade">Grade *</label>
                    <select id="grade" name="grade" required>
                        <option value="">Select a grade</option>
                        <?php foreach ($allowedGrades as $allowedGrade): ?>
                            <option value="<?= escape($allowedGrade) ?>" <?= $grade === $allowedGrade ? 'selected' : '' ?>>
                                <?= escape($allowedGrade) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field field-email">
                    <label for="email">Email address *</label>
                    <input type="email" id="email" name="email" value="<?= escape($email) ?>" maxlength="254" autocomplete="email" required>
                </div>
            </div>

            <div class="actions">
                <button type="submit">Validate student details</button>
            </div>
        </form>
        </main>
    </div>
</body>
</html>