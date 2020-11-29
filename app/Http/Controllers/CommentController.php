<?php
namespace App\Http\Controllers;

use App\Comment;
use App\Subcomment;
use Illuminate\Http\Request;
use View;
use Avatar;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'send' => 'required|max:100',
            'email' => 'required|email|max:250',
            'comment' => 'required|min:5|max:2000'
        ));

        $id = $request->id;

        $comment = new Comment();
        $comment->name = $request->send;
        $comment->email = $request->email;
        $comment->comment = clean($request->comment);
        $comment->approved = 1;
        $comment->pro_id = $id;

        $check = $comment->save();

        $arr = array(
            'success' => 'Something goes to wrong. Please try again lator',
            'status' => false
        );

        if ($check)
        {

            $arr = array(
                'success' => 'Successfully submit form using ajax',
                'status' => true,
                'msg' => $comment->comment,
                'email' => $comment->email,
                'name' => $comment->name,
                'date' => $comment->created_at,
                'id' => $comment->id
            );

        }

        return view('front.appendComment', compact('arr'));

    }

    public function loadmore(Request $request)
    {

        $output = '';
        $id = $request->id;
        $proid = $request->proid;

        $comments = Comment::where('pro_id', $proid)->where('id', '<', $id)->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        if (!$comments->isEmpty())
        {
            foreach ($comments as $comment)
            {

                $output .= '<div class="blog-detail-author-block mrg-btm-40">
                             <div class="row no-pad">
                                
                                <div class="col-lg-offset-1 col-lg-1">
                                  <img title="' . $comment->name . '" src="' . Avatar::create($comment->name)
                    ->toBase64() . '" width="70px"/>
                                </div>

                                <div class="col-lg-9">
                                            <div class="author-discription">
                                                <div class="row">
                                                    <div class="col-lg-6 col-6">
                                                        <div class="author-name"><a href="#" title=' . $comment->name . '>' . $comment->name . '</a></div>
                                                    </div>
                                                    <div class="col-lg-6 col-6">
                                                        <div class="author-date text-right"><a href="#" title=' . Carbon::parse($comment->created_at)
                    ->diffForHumans() . '>' . Carbon::parse($comment->created_at)
                    ->diffForHumans() . '</a></div>
                                                    </div>
                                                </div>
                                                <p>' . $comment->comment . '</p>
                                                
                                            </div>
                                </div>
                               
                            </div>
                        </div><br>';
            }

            $output .= '<div align="center" id="remove-row">
                            <button data-proid="' . $proid . '" data-id="' . $comment->id . '" class="btn-more btn btn-sm btn-info">Load More...</button>
                        </div><br>';

            echo $output;
        }
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $this->validate($request, array(
            'comment' => 'required'
        ));

        $comment->comment = clean($request->comment);
        $comment->save();

        Session::flash('success', 'Comment Updated Successfully');
        return redirect()
            ->route('posts.show', $comment
            ->post
            ->id);
    }

    

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        Session::flash('success', 'Comment Deleted');
        $post_id = $comment
            ->post->id;
        return redirect()
            ->route('posts.show', $post_id);
    }

  
    public function ajex_submit(Request $request)
    {

        $comment = new Subcomment();
        $comment->comment_id = $request->id;
        $comment->comment = clean($request->comment);
        $comment->approved = 1;
        $check = $comment->save();

        $arr = array(
            'success' => 'Something goes to wrong. Please try again lator',
            'status' => false
        );
        if ($check)
        {
            $arr = array(
                'success' => 'Successfully submit form using ajax',
                'status' => true,
                'msg' => $request->comment
            );

        }

        return Response()
            ->json($arr);

    }

}

