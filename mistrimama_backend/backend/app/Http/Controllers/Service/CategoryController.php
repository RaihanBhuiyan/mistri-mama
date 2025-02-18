<?php

namespace App\Http\Controllers\Service;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\QuickOrderItemsResource;
use App\Service;
use App\ServiceBit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('position', 'asc')->get();
        return view('category.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name'        => 'required|max:55',
            'description' => 'string',
            'benifits'    => 'string',
            'name_bn'        => 'required|max:55',
            'description_bn' => 'string',
            'benifits_bn'    => 'string',
            'position'    => 'required|numeric',
        ]);

        $data['slug'] = str_slug($request->name);
        //$data['description_bn'] = $request->description_bn);
        //dd($request);

        try
        {
            DB::beginTransaction();

            if ($request->has('thumb') && $request->thumb != '') {
                // file upload funtion base64_to_image($request->thumb , $location_path)
                //$data['thumb'] = $request->file('thumb')->store('/');
                $data['thumb'] = base64_to_image($request->thumb, 'upload/categories');
            }
    
            if ($request->has('icon') && $request->icon != '') {
                // file upload funtion base64_to_image($request->icon , $location_path)
                // $data['icon'] = $request->file('icon')->store('/');
                $data['icon'] = base64_to_image($request->icon, 'upload/categories');
            }
    
            if ($request->has('opt_image') && $request->opt_image != '') {
                // file upload funtion base64_to_image($request->icon , $location_path)
                // $data['opt_image'] = $request->file('opt_image')->store('/');
                $data['opt_image'] = base64_to_image($request->opt_image, 'upload/categories');
            }

            Category::create($data);

            DB::commit(); 

            toastr()->success('Category upload successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Category upload falied');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return Category::where('slug', $slug)->first();
    }

    public function quickOrderItems(Request $request)
    {
        $name = $request->find; 
        $category_id = Category::where('name', 'like', '%'.$name.'%')->first(); 
        if($category_id){
            $ServiceBit = ServiceBit::where('category_id', $category_id->id)->where(function($query) use ($name) {
                $query->where('tags', 'LIKE', '%'.$name.'%')
                    ->orWhere('name', 'LIKE', '%'.$name.'%');
            })->get();
        }else{
            $ServiceBit = ServiceBit::where('tags', 'like', '%'.$name.'%')->orWhere('name', 'like', '%'.$name.'%')->get();
        }

        return QuickOrderItemsResource::collection($ServiceBit);

        // SELECT * FROM `service_bits` WHERE service_bits.category_id = 6 AND (service_bits.name like '%ac%' OR service_bits.tags Like '%ac%'

        //return QuickOrderItemsResource::collection(ServiceBit::where('tags', 'like', '%'.$name.'%')->orWhere('name', 'like', '%'.$name.'%')->get());

    }

    public function category(Request $request){
        echo $name = $request->find;
        //$parents = Category::where('name', 'like', '%'.$name.'%')->get();
        return json_decode(Category::where('name', 'like', '%'.$name.'%')->orWhere('name', 'like', '%'.$name.'%')->get());
        exit();
        foreach ($parents as $parent) {
            $childs = Category::where('parent_id', $parent->id)->get();
            if (count($childs) > 0) {
                $subCat = array();
                $players = array();
                $roster[$parent->name] = $players;
                        foreach ($childs as $i => $child) {
                            $subchilds = Category::where('parent_id', $child->id)->get();
                            if (count($subchilds) > 0) {

                                $roster[$parent->name][$child->name] = $subCat;
                                foreach ($subchilds as $subchild) {

                                    $roster[$parent->name][$child->name][$subchild->id] = $subchild->name;
                                }

                            }else{
                                $roster[$parent->name][$child->name] = $players;
                            }
                        }

            }
        }
        return $roster;
    }
    public function allService($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return new CategoryResource($category);
    }

    public function allServiceById($id)
    {
        $category = Category::find($id);
        return new CategoryResource($category);
    }

    public function categoryList()
    {
        return CategoryResource::collection(Category::orderBy('position', 'asc')->get());
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('category.edit', compact('category'));
    }


    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $data = $request->validate([
            'name'        => 'required|max:55',
            'description' => 'string',
            'benifits'    => 'string',
            'name_bn'     => 'required|max:55',
            'description_bn' => 'string',
            'benifits_bn'    => 'string',
            'position'    => 'required|numeric',
        ]);

        try
        {
            DB::beginTransaction();

            if ($request->has('thumb') && $request->thumb != '') {
                //Storage::delete($request->thumb);
                //$data['thumb'] = $request->file('thumb')->store('/');
                $data['thumb'] = base64_to_image($request->thumb, 'upload/categories');
                if(!empty($category->thumb))
                {
                    $thumb_path = public_path('upload/categories/'.$category->thumb);
                    if (file_exists($thumb_path))
                    {
                        unlink($thumb_path);
                    }
                }
            }
    
            if ($request->has('icon') && $request->icon != '') {
                // Storage::delete($request->icon);
                // $data['icon'] = $request->file('icon')->store('/');
                $data['icon'] = base64_to_image($request->icon, 'upload/categories');
                if(!empty($category->icon))
                {
                    $icon_path = public_path('upload/categories/'.$category->icon);
                    if (file_exists($icon_path))
                    {
                        unlink($icon_path);
                    }
                }
            }
    
            if ($request->has('opt_image') && $request->opt_image != '') {
                // Storage::delete($request->opt_image);
                // $data['opt_image'] = $request->file('opt_image')->store('/');
                $data['opt_image'] = base64_to_image($request->opt_image, 'upload/categories');
                if(!empty($category->opt_image))
                {
                    $opt_image_path = public_path('upload/categories/'.$category->opt_image);
                    if (file_exists($opt_image_path))
                    {
                        unlink($opt_image_path);
                    }
                }
            }
            
            $category->update($data);

            DB::commit();

            toastr()->success('Category update successfull');
            return redirect()->route('category.index');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Category update failed');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $category = Category::find($id);
        
        if($category->services->count() > 0)
        {
            toastr()->error('Category can not be delete. This subordinate services must be deleted first.');
            return redirect()->back();
        }

        try
        {
            DB::beginTransaction();
            if(!empty($category->thumb))
            {
                $thumb_path = public_path('upload/categories/'.$category->thumb);
                if (file_exists($thumb_path))
                {
                    unlink($thumb_path);
                }
            }
            if(!empty($category->icon))
            {
                $icon_path = public_path('upload/categories/'.$category->icon);
                if (file_exists($icon_path))
                {
                    unlink($icon_path);
                }
            }
            if(!empty($category->opt_image))
            {
                $opt_image_path = public_path('upload/categories/'.$category->opt_image);
                if (file_exists($opt_image_path))
                {
                    unlink($opt_image_path);
                }
            }
            $category->delete();

            DB::commit();

            toastr()->success('Category deleted successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Category Delete Failed');
        }
        return redirect()->back();
    }
}
