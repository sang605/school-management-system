<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/login.php');
    exit();
}

$students = [
    ['id' => 1, 'name' => 'Aarav Sharma', 'grade' => 'Grade 8', 'email' => 'aarav@example.com'],
    ['id' => 2, 'name' => 'Maya Rai', 'grade' => 'Grade 7', 'email' => 'maya@example.com'],
    ['id' => 3, 'name' => 'Samir Thapa', 'grade' => 'Grade 9', 'email' => 'samir@example.com'],
];

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Students</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem auto; max-width: 900px; padding: 0 1rem; }
        header, .toolbar { align-items: center; display: flex; gap: 1rem; justify-content: space-between; }
        .notice { background: #fff4d6; padding: .75rem; }
        input { box-sizing: border-box; max-width: 100%; padding: .6rem; width: 320px; }
        table { border-collapse: collapse; margin-top: 1rem; width: 100%; }
        th, td { border-bottom: 1px solid #ddd; padding: .75rem; text-align: left; }
        th { background: #f4f4f4; }
        @media (max-width: 600px) {
            header, .toolbar { align-items: flex-start; flex-direction: column; }
            table { font-size: .9rem; }
            th, td { padding: .5rem .25rem; }
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>Students</h1>
            <p>View and search student records.</p>
        </div>
        <nav aria-label="Student navigation">
            <a href="add.php">Add student</a>
            <a href="../../dashboard.php">Back to dashboard</a>
        </nav>
    </header>

    <p class="notice">These are sample records for the page layout. They are not stored in a database.</p>

    <main>
        <div class="toolbar">
            <label for="student-search">Search students</label>
            <input type="search" id="student-search" placeholder="Search by name, grade, or email">
        </div>

        <table>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="student-list">
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= escape($student['name']) ?></td>
                        <td><?= escape($student['grade']) ?></td>
                        <td><?= escape($student['email']) ?></td>
                        <td><a href="edit.php?id=<?= (int) $student['id'] ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p id="no-results" hidden>No matching students found.</p>
    </main>

    <script>
        const searchInput = document.getElementById('student-search');
        const rows = [...document.querySelectorAll('#student-list tr')];
        const noResults = document.getElementById('no-results');

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const matches = row.textContent.toLowerCase().includes(query);
                row.hidden = !matches;
                if (matches) visibleCount += 1;
            });

            noResults.hidden = visibleCount > 0;
        });
    </script>
</body>
</html>