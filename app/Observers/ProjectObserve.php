<?php
namespace App\Observers;
use App\Models\Project;

class ProjectObserver {
    public function created(Project $project) {
        createNotification('Admin', "New project submitted by {$project->user->name}");
        createNotification(null, "Your project submitted successfully", $project->user_id);
    }

    public function updated(Project $project) {
        createNotification(null, "You updated project: {$project->name}", $project->user_id, 'edit');
    }

    public function deleted(Project $project) {
        createNotification(null, "You deleted project: {$project->name}", $project->user_id, 'delete');
    }
}
