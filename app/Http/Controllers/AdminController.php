<?php

namespace App\Http\Controllers;

use App\Models\service;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function unaccepted_services()
    {
        $services = service::whereNull('accept_at')->get();

        return response()->json(['unaccepted' => $services]);
    }
    public function makeServiceaccept($id)
    {
        $service = service::findOrFail($id);

        if ($service->accept_at) {
            return response()->json(
                ['error' => 'This service has already been accepted.'],
                400
            );
        }

        $service->accept_at = now();
        $service->save();

        return response()->json(
            ['success' => 'Service accepted successfully.'],
            200
        );
    }

    public function unaccepted_projects()
    {
        $project = Project::whereNull('accept_at')->get();

        return response()->json(['unaccepted' => $project]);
    }

    public function makeProjectaccept($id)
    {
        $Project = Project::findOrFail($id);

        if ($Project->accept_at) {
            return response()->json(
                ['error' => 'This service has already been accepted.'],
                400
            );
        }

        $Project->accept_at = now();
        $Project->save();

        return response()->json(
            ['success' => 'Service accepted successfully.'],
            200
        );
    }
}
