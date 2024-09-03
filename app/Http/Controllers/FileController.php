<?php

namespace App\Http\Controllers;

use App\Models\Embedding\File;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf'],
        ]);
        if($validator->fails()) {
            return response()->json(['status' => "failed", "trace" => $validator->errors()], 400);
        }

        $user = $request->user();
        $team = $user?->currentTeam;
        if (!$team)
            return response()->json(['status' => "failed", "trace" => ["No current team."]], 500);
        $file = $request->file('file');
        $path = $file->storeAs('uploads/', $team->id . '/' . $file->hashName());
        try {
            $insertedFile = File::insertGetId([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'team_id' => $team->id,
            ]);
            if ($insertedFile)
                (new JobService())->batchEmbedFile(File::find($insertedFile));
            return response()->json(['status' => "success", "trace" => ["file" => $insertedFile]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => "failed", "trace" => [$e->getMessage()]], 500);
        }
    }
}
