<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">管理ダッシュボード</h2>
    </x-slot>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📱 Applications管理</h3>
                <p class="text-gray-600 mb-4">アプリケーションの作成・編集・削除ができます。</p>
                <a class="inline-block px-4 py-2 rounded-md transition-colors" 
                   style="background-color: #9ca3af; color: white;"
                   onmouseover="this.style.backgroundColor='#6b7280'"
                   onmouseout="this.style.backgroundColor='#9ca3af'"
                   href="{{ route('admin.applications.index') }}">
                    Applications 管理
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📬 Contacts管理</h3>
                <p class="text-gray-600 mb-4">お問い合わせ内容の確認・管理ができます。</p>
                <a class="inline-block px-4 py-2 rounded-md transition-colors" 
                   style="background-color: #9ca3af; color: white;"
                   onmouseover="this.style.backgroundColor='#6b7280'"
                   onmouseout="this.style.backgroundColor='#9ca3af'"
                   href="{{ route('admin.contacts.index') }}">
                    Contacts 管理
                </a>
            </div>
        </div>
        
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">🔗 問い合わせフォームURL</h3>
                <a class="px-3 py-1 rounded text-sm transition-colors" 
                   style="background-color: #9ca3af; color: white;"
                   onmouseover="this.style.backgroundColor='#6b7280'"
                   onmouseout="this.style.backgroundColor='#9ca3af'"
                   href="{{ route('admin.contacts.index') }}">
                    📬 問い合わせ一覧 ({{ $totalContacts }}件)
                </a>
            </div>
            <p class="text-gray-600 mb-4">
                各Applicationの問い合わせフォームは以下のURL形式でアクセスできます：
            </p>
            
            @if($applications->count() > 0)
                <div class="bg-white rounded-md p-4 border mb-4">
                    <h4 class="font-semibold text-gray-800 mb-3">📱 各Applicationの問い合わせフォーム</h4>
                    <div class="space-y-2">
                        @foreach($applications as $app)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $app->name }}</span>
                                    <code class="ml-2 text-blue-600 font-mono text-sm">{{ url('/contact/' . $app->slug) }}</code>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">({{ $app->contacts_count }}件)</span>
                                    <a href="{{ route('contacts.form', ['app' => $app->slug]) }}" 
                                       target="_blank"
                                       class="text-xs px-2 py-1 rounded transition-colors"
                                       style="background-color: #9ca3af; color: white;"
                                       onmouseover="this.style.backgroundColor='#6b7280'"
                                       onmouseout="this.style.backgroundColor='#9ca3af'">
                                        📝 フォームを見る
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-md p-4 border">
                    <code class="text-blue-600 font-mono">
                        {{ url('/contact/{slug}') }}
                    </code>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    ※ {slug} は各Applicationのslugに置き換えられます
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
