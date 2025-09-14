<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Application;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $q = Contact::with('application')->latest();

        // ログインユーザーのApplicationsに関連するContactsのみを取得
        $q->whereHas('application', fn($qq) => $qq->where('user_id', auth()->id()));

        if ($app = $request->query('app')) {
            $q->whereHas('application', fn($qq) => $qq->where('slug', $app)->where('user_id', auth()->id()));
        }
        if ($application_id = $request->query('application_id')) {
            // application_idがログインユーザーのものかチェック
            $userApplication = Application::where('id', $application_id)
                                         ->where('user_id', auth()->id())
                                         ->first();
            if ($userApplication) {
                $q->where('application_id', $application_id);
            }
        }
        if ($cat = $request->query('category')) {
            $q->where('category', $cat);
        }
        if ($rating = $request->query('rating')) {
            $q->where('rating', $rating);
        }

        $contacts = $q->paginate(20)->withQueryString();
        
        // ログインユーザーのApplicationsのみを取得
        $apps = Application::where('user_id', auth()->id())->orderBy('name')->get();
        
        // 選択されたApplicationを取得（ユーザー所有かチェック）
        $selectedApplication = null;
        if ($application_id) {
            $selectedApplication = Application::where('id', $application_id)
                                             ->where('user_id', auth()->id())
                                             ->first();
        }

        return view('admin.contacts.index', compact('contacts','apps','selectedApplication'));
    }

    public function show(Contact $contact)
    {
        $contact->load('application');
        
        // ログインユーザーのApplicationに関連するContactかチェック
        if ($contact->application->user_id !== auth()->id()) {
            abort(403, 'このお問い合わせにアクセスする権限がありません。');
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        // ログインユーザーのApplicationに関連するContactかチェック
        if ($contact->application->user_id !== auth()->id()) {
            abort(403, 'このお問い合わせを削除する権限がありません。');
        }
        
        $contact->delete();
        return back()->with('ok','削除しました');
    }
}
