<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Contacts</h2></x-slot>
<div class="p-6">
  @if(session('ok'))<div class="mb-3 text-green-700">{{ session('ok') }}</div>@endif

  <form method="GET" class="mb-4 flex gap-2">
    <select name="app" class="border p-2">
      <option value="">(アプリ指定なし)</option>
      @foreach($apps as $app)
        @php
          $isSelected = false;
          if (request('app') === $app->slug) {
            $isSelected = true;
          } elseif (isset($selectedApplication) && $selectedApplication && $selectedApplication->id === $app->id) {
            $isSelected = true;
          }
        @endphp
        <option value="{{ $app->slug }}" @selected($isSelected)>{{ $app->name }}</option>
      @endforeach
    </select>
    <select name="category" class="border p-2">
      <option value="">(カテゴリ指定なし)</option>
      <option value="bug" @selected(request('category')==='bug')>不具合報告</option>
      <option value="request" @selected(request('category')==='request')>機能要望</option>
      <option value="payment" @selected(request('category')==='payment')>課金/購入</option>
      <option value="other" @selected(request('category')==='other')>その他</option>
    </select>
    <select name="rating" class="border p-2">
      <option value="">(評価指定なし)</option>
      @for($i=1;$i<=5;$i++)
        <option value="{{ $i }}" @selected((string)request('rating')===(string)$i)>{{ $i }}</option>
      @endfor
    </select>
    <button class="bg-gray-700 text-white px-3 rounded">絞り込み</button>
  </form>

  <table class="w-full border">
    <thead><tr class="bg-gray-100">
      <th class="p-2 border">ID</th>
      <th class="p-2 border">App</th>
      <th class="p-2 border">Category</th>
      <th class="p-2 border">Title</th>
      <th class="p-2 border">Rating</th>
      <th class="p-2 border">Created</th>
      <th class="p-2 border">Action</th>
    </tr></thead>
    <tbody>
    @foreach($contacts as $c)
      <tr>
        <td class="p-2 border">{{ $c->id }}</td>
        <td class="p-2 border">{{ $c->application->name }}</td>
        <td class="p-2 border">
          @switch($c->category)
            @case('bug')
              不具合報告
              @break
            @case('request')
              機能要望
              @break
            @case('payment')
              課金/購入
              @break
            @case('other')
              その他
              @break
            @default
              {{ $c->category }}
          @endswitch
        </td>
        <td class="p-2 border">{{ $c->title }}</td>
        <td class="p-2 border">{{ $c->rating }}</td>
        <td class="p-2 border">{{ $c->created_at }}</td>
        <td class="p-2 border">
          <a class="text-blue-600" href="{{ route('admin.contacts.show',$c) }}">詳細</a>
          <form method="POST" action="{{ route('admin.contacts.destroy',$c) }}" class="inline">
            @csrf @method('DELETE')
            <button class="text-red-600" onclick="return confirm('削除しますか？')">削除</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="mt-4">{{ $contacts->links() }}</div>
</div>
</x-app-layout>
