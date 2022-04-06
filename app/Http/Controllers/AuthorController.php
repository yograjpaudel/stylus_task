<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\View;
use App\Http\Requests\AuthorStoreRequest;
use App\Http\Requests\AuthorUpdateRequest;

class AuthorController extends Controller
{
    protected $model, $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->model = new Author;
        $this->databaseManager = $databaseManager;
    }
    public function index()
    {
        $data['rows'] = $this->model->latest()->get();

        return view('author.index', compact('data'));
    }

    public function create()
    {
        return view('author.create');
    }

    public function store(AuthorStoreRequest $request)
    {
        $request->request->add(['created_by' => auth()->user()->id]);
        try {
            $this->databaseManager->beginTransaction();
            $record = $this->model->create($request->all());
            $this->databaseManager->commit();
            toast('Author created successfully!','success','top-right');

        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Author creation failed!','error','top-right');
        }

        return redirect() -> route('author.index');
    }

    public function show($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Author not found for id ' . $id . '!');
            return redirect() -> route('author.index');
        }
        return view('author.show', compact('data'));
    }

    public function edit($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Author not found for id  ' . $id  . '!');
            return redirect() -> route($this->base_route . 'index');
        }
        return view('author.edit', compact('data'));
    }

    public function update(AuthorUpdateRequest $request, $id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Author not found for id  ' . $id  . '!');
            return redirect() -> route('author.index');
        }     
        $request->request->add(['updated_by' => auth()->user()->id]);

        try {
            $this->databaseManager->beginTransaction();            
            $data['row']->update($request->all());
            $this->databaseManager->commit();
            toast('Author updated successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Author Update failed!','error','top-right');
        }

        return redirect() -> route('author.index');
    }

    public function destroy($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Author not found for id  ' . $id  . '!');
            return redirect() -> route('author.index');
        }
        try {
            $this->databaseManager->beginTransaction();
            $data['row']->delete();
            $this->databaseManager->commit();
            toast('Author deleted successfully!','success','top-right');
        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            toast('Author delete failed!','success','top-right');
        }
        return redirect() -> route('author.index');
    }

    public function trash()
    {
        $data['rows'] = $this->model->onlyTrashed()->latest('deleted_at')->get();
        return view('author.trash', compact('data'));
    }

    public function restore($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Author not found for id  ' . $id  . '!');
            return redirect() -> route('author.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->restore();
            $this->databaseManager->commit();
            toast('Author restored successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Author restored failed!','success','top-right');
        }
        return redirect() -> route('author.trash');
    }

    public function forceDelete($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Author not found for id  ' . $id  . '!');
            return redirect() -> route('author.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->forceDelete();
            $this->databaseManager->commit();
            toast('Author permanently deleted successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Author delete failed!','success','top-right');
        }
        return redirect() -> route('author.trash');
    }
}
