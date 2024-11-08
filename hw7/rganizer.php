<?php

class Organizer {
    private $tasks = [];

    public function addTask($title, $description, DateTime $date) {
        $this->tasks[] = [
            'title' => $title,
            'description' => $description,
            'date' => $date
        ];
    }

    public function cancelTask($title) {
        foreach ($this->tasks as $index => $task) {
            if ($task['title'] === $title) {
                unset($this->tasks[$index]);
                $this->tasks = array_values($this->tasks);
                return true;
            }
        }
        return false;
    }

    public function getTasksForPeriod($period) {
        $now = new DateTime();
        $filteredTasks = [];

        foreach ($this->tasks as $task) {
            $interval = $now->diff($task['date']);

            if ($period === 'all' ||
                ($period === 'day' && $interval->days === 0) ||
                ($period === 'week' && $interval->days <= 7) ||
                ($period === 'month' && $interval->days <= 30)) {
                $filteredTasks[] = $task;
            }
        }
        return $filteredTasks;
    }

}
