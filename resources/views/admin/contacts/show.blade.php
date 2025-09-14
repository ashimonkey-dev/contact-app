<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Contact #{{ $contact->id }}</h2></x-slot>
<div class="p-6 space-y-2">
  <div>App: {{ $contact->application->name }} ({{ $contact->application->slug }})</div>
  <div>カテゴリ: {{ $contact->category }}</div>
  <div>タイトル: {{ $contact->title }}</div>
  <div>評価: {{ $contact->rating }}</div>
  <div>お名前: {{ $contact->name }}</div>
  <div>IP: {{ $contact->ip }}</div>
  <div>UA: {{ $contact->user_agent }}</div>
  <div>内容:</div>
  <pre class="border p-3 whitespace-pre-wrap">{{ $contact->detail }}</pre>
  <a class="text-blue-600" href="{{ route('admin.contacts.index') }}">戻る</a>
</div>
</x-app-layout>
