<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $apps = Application::withCount('contacts')
                          ->with('user')
                          ->where('user_id', auth()->id())
                          ->latest()
                          ->paginate(20);
        return view('admin.applications.index', compact('apps'));
    }

    public function create()
    {
        return view('admin.applications.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        
        // ランダムなslugを生成（重複しないまで繰り返し）
        do {
            $slug = 'app_' . substr(md5(uniqid(rand(), true)), 0, 12);
        } while (Application::where('slug', $slug)->exists());
        
        $data['slug'] = $slug;
        $data['user_id'] = auth()->id(); // ログインユーザーを設定
        
        $application = Application::create($data);
        
        // API Keyを自動生成
        $application->generateApiKey();
        
        return redirect()->route('admin.applications.index')->with('ok','作成しました');
    }

    public function edit(Application $application)
    {
        // ログインユーザーのApplicationかチェック
        if ($application->user_id !== auth()->id()) {
            abort(403, 'このアプリケーションを編集する権限がありません。');
        }
        
        return view('admin.applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        // ログインユーザーのApplicationかチェック
        if ($application->user_id !== auth()->id()) {
            abort(403, 'このアプリケーションを更新する権限がありません。');
        }
        
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        
        // slugは更新しない（既存のslugを保持）
        $application->update($data);
        return redirect()->route('admin.applications.index')->with('ok','更新しました');
    }

    public function destroy(Application $application)
    {
        // ログインユーザーのApplicationかチェック
        if ($application->user_id !== auth()->id()) {
            abort(403, 'このアプリケーションを削除する権限がありません。');
        }
        
        $application->delete();
        return back()->with('ok','削除しました');
    }
}
