<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\View;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Intervention\Image\Facades\Image;
use Auth;

class CategoryController extends Controller
{
    protected $image_path, $model, $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->image_path = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR;
        $this->model = new Category;
        $this->databaseManager = $databaseManager;
    }

    protected function uploadImage(Request $request, $image_field_name)
    {
        $image      = $request->file($image_field_name);
        $image_name = rand(6785, 9814).'_'.$image->getClientOriginalName();
        $image->move($this->image_path, $image_name);
        if(config('image_dimension.image_dimensions.category.image')) {
            $dimensions = config('image_dimension.image_dimensions.category.image');
            foreach ($dimensions as $dimension) {
                // open and resize an image file
                $img = Image::make($this->image_path.$image_name)->resize($dimension['width'], $dimension['height']);
                // save the same file as jpg with default quality
                $img->save($this->image_path.$dimension['width'].'_'.$dimension['height'].'_'.$image_name);
            }
        }
        return $image_name;
    }

    protected function deleteImage($image)
    {
        $image_name = $this->image_path .$image;
        if (file_exists($image_name)){
            unlink($image_name);
        }
        if(config('image_dimension.image_dimensions.category.image')) {
            $dimensions = config('image_dimension.image_dimensions.category.image');

            foreach ($dimensions as $dimension) {
                $path = $this->image_path.$dimension['width'].'_'.$dimension['height'].'_'.$image;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
    }

    public function index()
    {
        $data['rows'] = $this->model->latest()->get();

        return view('parent.category.index', compact('data'));
    }

    public function create()
    {
        return view('parent.category.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        if ($request->hasFile('photo')) {
            $image = $this->uploadImage($request,'photo');
            $request->request->add(['image' => $image]);
        }
        $request->request->add(['created_by' => auth()->user()->id]);
        try {
            $this->databaseManager->beginTransaction();
            $record = $this->model->create($request->all());
            $this->databaseManager->commit();
            toast('Category created successfully!','success','top-right');

        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            if (isset($image)) {
                $this->deleteImage($image);
            }
            toast('Category creation failed!','error','top-right');
        }

        return redirect() -> route('parent.category.index');
    }

    public function show($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Category not found for id ' . $id . '!');
            return redirect() -> route('parent.category.index');
        }
        return view('parent.category.show', compact('data'));
    }

    public function edit($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message',  'Category not found for id  ' . $id  . '!');
            return redirect() -> route($this->base_route . 'index');
        }
        return view('parent.category.edit', compact('data'));
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Category not found for id  ' . $id  . '!');
            return redirect() -> route('parent.category.index');
        }     
        $request->request->add(['updated_by' => auth()->user()->id]);

        try {
            $this->databaseManager->beginTransaction();            
            if ($request->hasFile('photo')) {
                $image = $this->uploadImage($request,'photo');
                $request->request->add(['image' => $image]);

                if ($data['row']->image) {
                    $this->deleteImage( $data['row']->image);
                }
            }
            $data['row']->update($request->all());
            $this->databaseManager->commit();
            toast('Category updated successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Category Update failed!','error','top-right');
        }

        return redirect() -> route('parent.category.index');
    }

    public function destroy($id)
    {
        if (!$data['row'] = $this->model->find($id)){
            request()->session()->flash('error_message', 'Category not found for id  ' . $id  . '!');
            return redirect() -> route('parent.category.index');
        }
        try {
            $this->databaseManager->beginTransaction();
            $data['row']->delete();
            $this->databaseManager->commit();
            toast('Category deleted successfully!','success','top-right');
        } catch (Exception $e) {
            $this->databaseManager->rollBack();
            toast('Category delete failed!','success','top-right');
        }
        return redirect() -> route('parent.category.index');
    }

    public function trash()
    {
        $data['rows'] = $this->model->onlyTrashed()->latest('deleted_at')->get();

        return view('parent.category.trash', compact('data'));
    }

    public function restore($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Category not found for id  ' . $id  . '!');
            return redirect() -> route('parent.category.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->restore();
            $this->databaseManager->commit();
            toast('Category restored successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Category restored failed!','success','top-right');
        }
        return redirect() -> route('parent.category.trash');
    }

    public function forceDelete($id)
    {
        if (!$data['row'] = $this->model->withTrashed()->find($id)){
            request()->session()->flash('error_message',  'Category not found for id  ' . $id  . '!');
            return redirect() -> route('parent.category.index');
        }
        try{
            $this->databaseManager->beginTransaction();
            $data['row']->forceDelete();
            if ($data['row']->image) {
                $this->deleteImage($data['row']->image);
            }
            $this->databaseManager->commit();
            toast('Category permanently deleted successfully!','success','top-right');
        } catch (\Exception $e) {
            $this->databaseManager->rollBack();
            toast('Category delete failed!','success','top-right');
        }
        return redirect() -> route('parent.category.trash');
    }
}
