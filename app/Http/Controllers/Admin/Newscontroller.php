<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

    //以下を追記することでNews modelが使えるようになる
    use App\News;

class Newscontroller extends Controller
{
    //以下を追記
    public function add()
    {
        return view('admin.news.create');
    }
    
    public function create(Request $request)
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
}
