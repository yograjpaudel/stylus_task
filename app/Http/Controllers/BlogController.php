<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Author;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\View;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    protected $model, $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->model = new Blog;
        $this->databaseManager = $databaseManager;
    }

    public function index()
    {
        $data['rows'] = $this->model->latest()->get();

        return view('blog.index', compact('data'));
    }

    public function create()
    {
        $data['authors'] = Author::all();
        $data['categories'] = Category::all();
        $data['tags'] = Tag::all();
        return view('blog.create', compact('data'));
    }

    public function store(BlogStoreRequest $request)
    {
        $request->request->add(['created_by' => auth()->user()->id]);
        try {
            $this->databaseManager->beginTransaction();
            $record = $this->model->create($request->all());
            $record->categories()->attach($request['category_ids']);
            $record->tags()->attach($request['tag_ids']);
            $this->databaseManager->commit();
            toast('Blog created successfully!','success','top-right');

        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Blog creation failed!','error','top-right');
        }

        return redirect() -> route('blog.index');
    }
    
    // code for ckeditor image upload
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('ckeditor/blog/images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('ckeditor/blog/images/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function show($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Blog not found for id ' . $id . '!');
            return redirect() -> route('blog.index');
        }
        return view('blog.show', compact('data'));
    }

    public function edit($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Blog not found for id  ' . $id  . '!');
            return redirect() -> route($this->base_route . 'index');
        }
        $data['authors'] = Author::all();
        $data['categories'] = Category::all();
        $data['tags'] = Tag::all();
        return view('blog.edit', compact('data'));
    }

    public function update(BlogUpdateRequest $request, $id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Blog not found for id  ' . $id  . '!');
            return redirect() -> route('blog.index');
        }     
        $request->request->add(['updated_by' => auth()->user()->id]);

        try {
            $this->databaseManager->beginTransaction();
            $data['row']->update($request->all());
            $data['row']->categories()->sync($request['category_ids']);
            $data['row']->tags()->sync($request['tag_ids']);
            $this->databaseManager->commit();
            toast('Blog updated successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Blog Update failed!','error','top-right');
        }

        return redirect() -> route('blog.index');
    }

    public function destroy($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Blog not found for id  ' . $id  . '!');
            return redirect() -> route('blog.index');
        }
        try {
            $this->databaseManager->beginTransaction();
            $data['row']->delete();
            $this->databaseManager->commit();
            toast('Blog deleted successfully!','success','top-right');
        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            toast('Blog delete failed!','success','top-right');
        }
        return redirect() -> route('blog.index');
    }

    public function trash()
    {
        $data['rows'] = $this->model->onlyTrashed()->latest('deleted_at')->get();

        return view('blog.trash', compact('data'));
    }

    public function restore($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Blog not found for id  ' . $id  . '!');
            return redirect() -> route('blog.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->restore();
            $this->databaseManager->commit();
            toast('Blog restored successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Blog restored failed!','success','top-right');
        }
        return redirect() -> route('blog.trash');
    }

    public function forceDelete($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Blog not found for id  ' . $id  . '!');
            return redirect() -> route('blog.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->categories()->detach();
            $data['row']->tags()->detach();
            $data['row']->forceDelete();
            $this->databaseManager->commit();
            toast('Blog permanently deleted successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Blog delete failed!','success','top-right');
        }
        return redirect() -> route('blog.trash');
    }
}
