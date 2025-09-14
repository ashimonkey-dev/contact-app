<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 固定カテゴリ（必要ならDB化OK）
    private const CATEGORIES = [
        'bug'     => '不具合報告',
        'request' => '機能要望',
        'payment' => '課金/購入',
        'other'   => 'その他',
    ];

    public function showForm(Request $request, string $app)
    {
        $application = Application::where('slug', $app)->firstOrFail();

        return view('contacts.form', [
            'application'      => $application,
            'categories'       => self::CATEGORIES,
            'selectedCategory' => $request->query('category'),
        ]);
    }

    public function store(Request $request, string $app)
    {
        $application = Application::where('slug', $app)->firstOrFail();

        $validated = $request->validate([
            'name'     => ['nullable','string','max:100'],
            'category' => ['required','in:'.implode(',', array_keys(self::CATEGORIES))],
            'title'    => ['nullable','string','max:255'],
            'rating'   => ['nullable','integer','min:1','max:5'],
            'detail'   => ['required','string','max:10000'],
        ], [], [
            'name' => 'お名前',
            'category' => 'カテゴリ',
            'title' => 'タイトル',
            'rating' => '評価',
            'detail' => '詳細',
        ]);

        $contact = Contact::create([
            ...$validated,
            'application_id' => $application->id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // セッションに送信完了フラグを保存
        $request->session()->flash('contact_submitted', true);
        $request->session()->flash('contact_id', $contact->id);

        return redirect()->route('contacts.thanks', ['app' => $app]);
    }

    public function thanks(Request $request, string $app)
    {
        $application = Application::where('slug', $app)->firstOrFail();

        // セッションフラグをチェック
        if (!$request->session()->has('contact_submitted')) {
            return redirect()->route('contacts.form', ['app' => $app]);
        }

        // 送信されたcontactを取得
        $contactId = $request->session()->get('contact_id');
        $contact = Contact::where('id', $contactId)
                         ->where('application_id', $application->id)
                         ->firstOrFail();

        // セッションフラグを削除（一度だけ表示）
        $request->session()->forget(['contact_submitted', 'contact_id']);

        return view('contacts.thanks', [
            'application' => $application,
            'contact'     => $contact,
        ]);
    }
}
