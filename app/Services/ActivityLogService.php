<?php

namespace App\Services;

use App\Models\ActivityModel;

class ActivityLogService
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityModel();
    }

    public function logActivity($action, $userId = null)
    {
        // Save the activity log
        $this->activityLogModel->insert([
            'user_id' => $userId,
            'task_done' => $action,
            'created_on' => date('Y-m-d H:i:s')
        ]);
    }
}
