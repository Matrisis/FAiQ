<?php

namespace App\Http\Controllers;

use App\Events\NewFileEvent;
use App\Models\Embedding\Embedding;
use App\Models\Embedding\File;
use App\Models\Team;
use App\Models\User;
use App\Services\BatchingService;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class FileController extends Controller
{

    public function index(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);
        return Inertia::render('Files', [
            'team' => $team,
        ]);
    }

    public function list(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);
        $files = File::where('team_id', $team->id);
        return DataTables::eloquent($files)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !empty($request->get('name'))) {
                    $query->where('name', 'like', '%' . $request->get('name') . '%');
                }
            })
            ->toJson();
    }

    public function store(Request $request, Team $team): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->user()->cannot("createFiles", $team)) {
                abort(403);
            }

            $validated = $request->validate([
                'files' => 'required|array',
                'files.*' => 'required|file|mimes:pdf|max:51200',
            ], [
                'files.required' => 'Vous devez sélectionner au moins un fichier.',
                'files.*.required' => 'Le fichier :attribute est requis.',
                'files.*.file' => 'Le fichier :attribute doit être un fichier valide.',
                'files.*.mimes' => 'Le fichier :attribute doit être de type :values.',
                'files.*.max' => 'Le fichier :attribute ne doit pas dépasser :max kilo-octets.',
            ]);

            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs("uploads/files/" . $team->id, $filename);
                File::create([
                    "name" => $file->getClientOriginalName(),
                    "path" => $path,
                    "team_id" => $team->id,
                ]);
            }

            broadcast(new NewFileEvent(channel:"file.".$team->id));

            return response()->json(["success" => true]);
        } catch (ValidationException $e) {
            broadcast(new NewFileEvent(channel:"file.".$team->id));
            return response()->json([
                "success" => false,
                "errors" => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "errors" => [["Une erreur est survenue : " . $e->getMessage()]]
            ], 500);
        }
    }

    public function delete(Request $request, Team $team, File $file) {
        if($request->user()->cannot("delete", $file)) abort(403);

        Storage::delete($file->path);
        $file->delete();
        Embedding::where("file_id", $file->id)->delete();

        return response()->json(["success" => true]);
    }

    public function process(Request $request, Team $team, File $file) {
        try {
            if ($request->user()->cannot("process", $file)) abort(403);
            if (!$file->importing && !$file->imported) {
                $batching_service = new BatchingService($team);
                $batching_service->createFile($file);
                $file->importing = true;
                $file->save();
            } else {
                response()->json(["success" => false, "errors" => [["File already processed or processing"]]], 422);
            }
        } catch (\Exception $e) {
            dd($e);
            return response()->json(["success" => false, "errors" => [["Une erreur est survenue"]]], 500);
        }
        return response()->json(["success" => true]);

    }
}
