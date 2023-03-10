<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $published = $request->query('published');
        $query = Project::orderBy('updated_at', 'DESC');
        if ($published) {
            $value = $published === 'published' ? 1 : 0;
            $query->where('published', $value);
        }
        $projects = $query->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $types = Type::all();
        return view('admin.projects.create', compact('project', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'project_img' => 'nullable|image',
            'description' => 'required|string',
            'project_link' => 'required|url',
            'type_id' => 'nullable|exists:types,id'

        ], [
            'name.required' => 'Devi inserire un nome valido!',
            'description.required' => 'Devi inserire una descrizione!',
            'project_img.image' => 'Il file caricato deve essere un\'immagine.',
            'project_link.required' => 'Devi inserire un link valido!',
            'project_link.url' => 'Il link del progetto non è valido.',
            'type_id.exists' => 'Il tipo di progetto inserito non è valido',

        ]);
        $data = $request->all();
        $project = new Project();
        if (Arr::exists($data, 'project_img')) {
            $data['project_img'] = Storage::put('projects', $data['project_img']);
        }
        $data['published'] = Arr::exists($data, 'published') ? 1 : 0;
        $project->fill($data);
        $project->save();

        return to_route('admin.projects.show', $project->id)->with('msg', "Il progetto $project->name è stato aggiunto correttamente.")->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string',
            'project_img' => 'nullable|image',
            'description' => 'required|string',
            'project_link' => 'required|url',
            'type_id' => 'nullable|exists:types,id'
        ], [
            'name.required' => 'Devi inserire un nome valido!',
            'description.required' => 'Devi inserire una descrizione!',
            'project_img.image' => 'Il file caricato deve essere un\'immagine.',
            'project_link.required' => 'Devi inserire un link valido!',
            'type_id.exists' => 'Il tipo di progetto inserito non è valido',
        ]);
        $data = $request->all();
        if (Arr::exists($data, 'project_img')) {
            if ($project->project_img) Storage::delete($project->project_img);
            $data['project_img'] = Storage::put('projects', $data['project_img']);
        }
        $data['published'] = Arr::exists($data, 'published') ? 1 : 0;
        $project->fill($data);
        $project->save();

        return to_route('admin.projects.show', $project->id)->with('msg', "Il progetto $project->name è stato modificato.")->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->project_img) Storage::delete($project->project_img);
        $project->delete();
        return to_route('admin.projects.index')->with('msg', "Il progetto $project->name è stato eliminato.")->with('type', 'danger');
    }
}
