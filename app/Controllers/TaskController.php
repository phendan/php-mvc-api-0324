<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Request;
use App\Models\{Task, FormValidation};

class TaskController extends BaseController
{
    public function update(Request $request)
    {
        if (!$this->user->isSignedIn()) {
            return $this->response->json(401);
        }

        $taskId = (int) $request->getParams()['taskId'];

        $task = new Task($this->db);

        if (!$task->find($taskId)) {
            return $this->response->json(404);
        }

        if ($task->getUserId() !== $this->user->getId()) {
            return $this->response->json(403);
        }

        $input = $request->getInput();

        $validation = new FormValidation($input, $this->db);

        $validation->setRules([
            'status' => 'required',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            return $this->response->json(422, [
                'errors' => $validation->getErrors()
            ]);
        }

        $task->update($input['status']);

        return $this->response->json(200, [
            'task' => $task->getData()
        ]);
    }
}
