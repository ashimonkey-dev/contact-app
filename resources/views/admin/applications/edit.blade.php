<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Application 編集</h2></x-slot>
<div class="p-6">
  <form method="POST" action="{{ route('admin.applications.update',$application) }}" class="space-y-4">
    @csrf @method('PUT')
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">アプリケーション名</label>
      <input name="name" value="{{ old('name',$application->name) }}" 
             class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
      @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Slug（読み取り専用）</label>
      <input value="{{ $application->slug }}" 
             class="border border-gray-300 rounded-md px-3 py-2 w-full bg-gray-100 text-gray-600" 
             readonly>
      <div class="text-sm text-gray-600 mt-1">Slugは変更できません</div>
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">更新</button>
  </form>
</div>
</x-app-layout>
