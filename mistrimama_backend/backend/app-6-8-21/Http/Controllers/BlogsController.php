<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Blogs;
use App\BlogCategory;
use App\BlogComment;
use App\BlogLikeHistory;
use App\Http\Resources\BlogArticleResource;
use App\Http\Resources\BlogCommentsResource;
use Auth;
use App\EmailTemplate;
use Illuminate\Support\Carbon;

class BlogsController extends Controller
{

    public function getArticles()
    {
        $article = Blogs::where('status', 1)->orderBy('published_date', 'desc')->get();
        return BlogArticleResource::collection($article);
    }

    public function articles()
    {
        $article = Blogs::where('status', 1)->orderBy('published_date', 'desc')->take(6)->get();
        return BlogArticleResource::collection($article);
    }

    public function article($id)
    {
        $article = Blogs::where(['id' => $id, 'status' => 1])->first();
        return new BlogArticleResource($article);
    }

    public function recent_articles()
    {
        $article = Blogs::where('status', 1)->take(3)->get()->random(3);
        return BlogArticleResource::collection($article);
    }

    public function index(Request $request)
    {
        $query = Blogs::query();
        if ($request->title) {
            $query->where('title', 'like', "%" . $request->title . "%");
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        $data['posts'] = $query->with('category')->orderBy('id', 'DESC')->paginate(20);
        return view('blogs.index', $data);
    }

    public function create()
    {
        $data['category'] = BlogCategory::all();
        return view('blogs.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'title_bn' => 'required',
            'url' => 'required',
            'image' => 'required',
            'short_description' => 'required',
            'short_description_bn' => 'required',
            'content' => 'required',
            'content_bn' => 'required',
            'status' => 'required|in:0,1',
            'meta_title' => 'required',
            'meta_keyword' => 'required',
            'meta_description' => 'required',
            'published_date' => 'required|date_format:Y-m-d',
        ]);
        
        try {
            DB::beginTransaction();

            $data['image'] = NULL;
            if ($request->has('image') && $request->image != '') {
                $data['image'] = base64_to_image($request->image, 'upload/blogs');
            }
            
            $data['category_id'] = 0;
            $data['users_id'] = auth()->user()->id;
            $data['slug'] = str_replace(" ", "_", $data['title']);

            Blogs::create($data);
            
            DB::commit();

            toastr()->success('Post has been successfully submit');
            
        } catch (Exception $e) {
            DB::rollback();
            toastr()->error('Oops ! Something was wrong. try again.');
        }
        return redirect(route('blog.index'));
    }

    public function show($id)
    {
        $data['author'] = auth()->user();
        $data['post'] = Blogs::find($id);
        return view('blogs.show', $data);
    }

    public function edit($id)
    {
        $data['posts'] = Blogs::find($id);
        return view('blogs.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'title' => 'required',
            'title_bn' => 'required',
            'url' => 'required',
            'short_description' => 'required',
            'content' => 'required',
            'short_description_bn' => 'required',
            'content_bn' => 'required',
            'status' => 'required|in:0,1',
            'meta_title' => 'required',
            'meta_keyword' => 'required',
            'meta_description' => 'required',
            'published_date' => 'required|date_format:Y-m-d',
        ]);

        $blog = Blogs::find($id);

        try {
            DB::beginTransaction();
            
            $data['image'] = $blog->image;
            if ($request->has('image') && $request->image != '') {
                $data['image'] = base64_to_image($request->image, 'upload/blogs');
            }
            
            $data['category_id'] = 0;
            $data['users_id'] = auth()->user()->id;
            $data['slug'] = str_replace(" ", "_", $data['title']);

            $blog->update($data);
            
            DB::commit();

            toastr()->success('Post has been successfully updated');
            
        } catch (Exception $e) {
            DB::rollback();
            toastr()->error('Oops ! Something was wrong. try again.');
        }
        return redirect(route('blog.index'));
    }

    public function destroy($id) {
        $delete = Blogs::find($id);
        $delete->forceDelete();
        return redirect(route('blog.index'));
    }

    public function getSlug(Request $request)
    {
        $slug = $request->get('slug');
        $blog_slug = myCreateUrl($slug);
        return response()->json(['blog_slug' => $blog_slug]);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|size:11|regex:/(01)[0-9]{9}/',
            'comments' => 'required',
        ]);

        $author = auth()->user();
        $comment_id = ($request->comment_id) ? $request->comment_id : NULL;

        try {
            DB::beginTransaction();
                $postComments = BlogComment::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'author_thumb' => (!empty($author)) ? $author->admin->photo_url : env('APP_URL').'/frontend/image/icons/author-thumb.png',
                    'post_id' => $id,
                    'comment_id' => $comment_id,
                    'message' => $request->comments,
                    'status' => 1,
                ]);
            DB::commit();

            $post = Blogs::find($id);
            $total_comments = $post->relComments->count();
            $comment = [
                'name' => $postComments->name,
                'phone' => $postComments->phone,
                'author_thumb' => (!empty($author)) ? $author->admin->photo_url : env('APP_URL').'/frontend/image/icons/author-thumb.png',
                'post_id' => $id,
                'message' => $postComments->message,
            ];
            return response()->json(['total_comments' => $total_comments, 'comment' => $comment, 'message' => 'Thank you for leave a comment.'], 200);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Leave comment failed. Try again'], 400);
        }
    }

    public function comment_removed($id)
    {
        $comment = BlogComment::find($id);
        try {
            DB::beginTransaction();
                if(empty($comment->comment_id)){
                    BlogComment::where('comment_id', $id)->delete();
                }
                BlogComment::where('id', $id)->delete();
            DB::commit();

            toastr()->success('Comment has been approved.');
            
        } catch (Exception $e) {
            DB::rollback();
            toastr()->error('Oops ! Something was wrong. try again.');
        }
        return redirect()->back();
    }

    public function like_article($id)
    {
        $ip = getIPAddress();

        $exists_id = BlogLikeHistory::where(['post_id' => $id, 'ip' => $ip])->exists();
        
        if($exists_id == false)
        {
            try {
                DB::beginTransaction();

                BlogLikeHistory::create([
                    'post_id' => $id,
                    'ip' => $ip,
                ]);
                DB::commit();

                $article = Blogs::find($id);

                return response()->json($article->total_like, 200);
                
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(NULL, 400);
            }
        }
        return response()->json(NULL, 400);
    }

}
