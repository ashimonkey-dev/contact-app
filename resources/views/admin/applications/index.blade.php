<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Applications</h2></x-slot>
<div class="p-6">
  @if(session('ok'))<div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('ok') }}</div>@endif
  
  <!-- 新規作成ボタン -->
  <div class="mb-6">
    <a class="inline-block font-bold py-2 px-4 rounded-lg shadow-lg transition-colors" 
       style="background-color: #9ca3af; color: white;"
       onmouseover="this.style.backgroundColor='#6b7280'"
       onmouseout="this.style.backgroundColor='#9ca3af'"
       href="{{ route('admin.applications.create') }}">
      ➕ 新規Application作成
    </a>
  </div>
  <table class="mt-4 w-full border">
    <thead>
      <tr class="bg-gray-100">
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Name</th>
        <th class="p-2 border">作成者</th>
        <th class="p-2 border">Slug</th>
        <th class="p-2 border">問い合わせ件数</th>
        <th class="p-2 border">問い合わせURL</th>
        <th class="p-2 border">Actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($apps as $a)
      <tr>
        <td class="p-2 border">{{ $a->id }}</td>
        <td class="p-2 border font-medium">{{ $a->name }}</td>
        <td class="p-2 border">
          @if($a->user)
            <span class="text-sm">{{ $a->user->name }}</span>
          @else
            <span class="text-sm text-gray-400">不明</span>
          @endif
        </td>
        <td class="p-2 border">
          <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $a->slug }}</code>
        </td>
        <td class="p-2 border text-center">
          @if($a->contacts_count > 0)
            <a href="{{ route('admin.contacts.index', ['application_id' => $a->id]) }}" 
               class="text-gray-600 hover:text-gray-800 text-sm">
              {{ $a->contacts_count }}件
            </a>
          @else
            <span class="text-gray-400 text-sm">{{ $a->contacts_count }}件</span>
          @endif
        </td>
        <td class="p-2 border">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <a href="{{ route('contacts.form', ['app' => $a->slug]) }}" 
                 target="_blank" 
                 class="inline-block text-xs px-2 py-1 rounded transition-colors"
                 style="background-color: #9ca3af; color: white;"
                 onmouseover="this.style.backgroundColor='#6b7280'"
                 onmouseout="this.style.backgroundColor='#9ca3af'">
                📝 フォームを見る
              </a>
            </div>
            <div class="text-xs text-gray-500 break-all">
              <code>{{ route('contacts.form', ['app' => $a->slug]) }}</code>
            </div>
          </div>
        </td>
        <td class="p-2 border">
          <div class="space-x-2">
            <a class="text-blue-600 hover:text-blue-800" href="{{ route('admin.applications.edit',$a) }}">編集</a>
            <form method="POST" action="{{ route('admin.applications.destroy',$a) }}" class="inline">
              @csrf @method('DELETE')
              <button class="text-red-600 hover:text-red-800" onclick="return confirm('削除しますか？')">削除</button>
            </form>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="mt-4">{{ $apps->links() }}</div>
</div>
</x-app-layout>
