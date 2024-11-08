<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Organizer</h1>

    <div class="form-container">
        <h2>Add New Task</h2>
        <form method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>

            <button type="submit">Add Task</button>
        </form>
    </div>

    <div class="tasks-container">
        <h2>Tasks for Today</h2>
        <?php
        require_once 'rganizer.php';
        session_start();

        if (!isset($_SESSION['organizer'])) {
            $_SESSION['organizer'] = serialize(new Organizer());
        }
        $organizer = unserialize($_SESSION['organizer']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'], $_POST['date'])) {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date = new DateTime($_POST['date']);
            $organizer->addTask($title, $description, $date);
            $_SESSION['organizer'] = serialize($organizer);
        }

        if (isset($_GET['cancel'])) {
            $titleToCancel = $_GET['cancel'];
            $organizer->cancelTask($titleToCancel);
            $_SESSION['organizer'] = serialize($organizer);
        }

        function displayTasks($tasks) {
            if (empty($tasks)) {
                echo "<p>No tasks planned.</p>";
            } else {
                echo "<ul>";
                foreach ($tasks as $task) {
                    echo "<li><strong>{$task['title']}</strong> - {$task['description']} 
                    <em>({$task['date']->format('Y-m-d')})</em> 
                    <a class='cancel-btn' href='?cancel={$task['title']}'>Cancel</a></li>";
                }
                echo "</ul>";
            }
        }

        displayTasks($organizer->getTasksForPeriod('day'));
        ?>

        <h2>Tasks for This Week</h2>
        <?php displayTasks($organizer->getTasksForPeriod('week')); ?>

        <h2>Tasks for This Month</h2>
        <?php displayTasks($organizer->getTasksForPeriod('month')); ?>
    </div>
</div>
</body>
</html>

