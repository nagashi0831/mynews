<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

    //以下を追記することでNews modelが使えるようになる
    use App\News;
    use App\History;
    use Carbon\Carbon;

class Newscontroller extends Controller
{
    //以下を追記
    public function add()
    {
        //これでviewを呼び出し
        return view('admin.news.create');
    }
     
    public function create(Request $request)//Requestクラスで取得したものを$requestに代入
    {
        //varidationを行う
        
        $this->validate($request, News::$rules);
        
        $news = new News;
        $form = $request->all();
        
        /*フォームから画像が送信されてきたら、保存して、$news->image_path に画像のパス
        を保存する*/
        if (isset($form['image'])) {
            $path = $request->file('image')->store('public/image');
            $news->image_path = null;
        }
        //フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        //フォームから送られてきたimageを削除する
        unset($form['image']);

        //データベースに保存する
        $news->fill($form);
        $news->save();

        return redirect('admin/news/create');
    }
    
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
        //検索されたら検索結果を取得する
            $post = News::where('title', $cond_title)->get();
        } else {
            //それ以外はすべてのニュースを取得する
            $post = News::all();
        }//ダブルアロー演算子は
        return view('admin.news.index', ['posts' => $post, 'cond_title' => 
        $cond_title]);
    }
    
    public function edit(Request $request)
    {
        //News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
    
    public function update(Request $request)
    {
        //Validationをかける
        $this->validate($request, News::$rules);
        //News Modelからデータを取得する
        $news = News::find($request->id);
        //送信されてきたフォームデータを格納する
        $news_form = $request->all();
        if ($request->remove == 'true') {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $news_form['image_path'] = basename($path);
        } else {
            $news_form['image_path'] = $news->image_path;
        }
        
        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['remove']);
        //該当するデータを上書きして保存する
        $news->fill($news_form)->save();
        
        //以下を追記
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        
        return redirect('admin/news/');
    }
    
    public function delete(Request $request)
    {
        //該当するNews Modelを取得
        $news = News::find($request->id);
        //削除する
        $news->delete();
        return redirect('admin/news/');
    }
}
