<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Application 作成</h2></x-slot>
<div class="p-6">
  <form method="POST" action="{{ route('admin.applications.store') }}" class="space-y-4">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">アプリケーション名</label>
      <input name="name" value="{{ old('name') }}" 
             class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
             placeholder="アプリケーション名を入力してください">
      @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
      <strong>注記:</strong> slug（URL用識別子）は自動的に生成されます。
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">保存</button>
  </form>
</div>
</x-app-layout>
