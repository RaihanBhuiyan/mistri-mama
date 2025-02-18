<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pages'] = Page::all();
        return view('page.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('page.create');
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
            'title' => 'required|unique:pages,slug',
        ]);

        $data['slug'] = str_slug($request->title);

        if ($request->has('media') && $request->media != '') {
            $data['media'] = base64_to_image($request->media, 'upload/web');
        }
        
        try
        {
            DB::beginTransaction();
            
            Page::create($data);
            
            DB::commit();

            toastr()->success('New Page created');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        toastr()->error('Something went wrong');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        try
        {
            DB::beginTransaction();
            
                if(!empty($page->media))
                {
                    $thumb_path = public_path('upload/web/'.$page->media);
                    if (file_exists($thumb_path))
                    {
                        unlink($thumb_path);
                    }
                }
                $page->forceDelete();
            
            DB::commit();

            toastr()->success('New Page created');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        toastr()->error('Something went wrong');
        return redirect()->back();
    }

    public function loadPageInformations($page){
        $page = Page::where('slug', $page)->first();
        if(!empty($page))
        {
            return response()->json($page->media_url, 200);
        }
        return response()->json(NULL, 200);
    }
}
