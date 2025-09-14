<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $application->name }}｜お問い合わせ</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    body{
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Noto Sans JP', sans-serif;
      padding: 16px;
      margin: 0;
      background-color: #ffffff;
      line-height: 1.5;
    }
    h1 {
      color: #1f2937;
      font-size: 1.5rem;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 2px solid #3b82f6;
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      font-weight: 600;
      color: #374151;
      margin-bottom: 4px;
      font-size: 0.9rem;
    }
    input[type="text"], input[type="email"], select, textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      font-size: 16px;
      box-sizing: border-box;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }
    input[type="text"]:focus, input[type="email"]:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    textarea {
      resize: vertical;
      min-height: 100px;
    }
    button[type="submit"] {
      width: 100%;
      background-color: #3b82f6;
      color: white;
      padding: 16px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 8px;
    }
    button[type="submit"]:hover {
      background-color: #2563eb;
    }
    .error{
      color: #dc2626;
      font-size: 0.875rem;
      margin-top: 4px;
    }
    .star{
      display: inline-flex;
      gap: 4px;
      margin-top: 8px;
    }
    .star input{
      display: none;
    }
    .star label{
      cursor: pointer;
      font-size: 2rem;
      line-height: 1;
      user-select: none;
    }
    .star label::before{
      content: '☆';
      color: #d1d5db;
    }
    .star label.selected::before{
      content: '★';
      color: #fbbf24;
    }
    .error-box {
      background: #fef2f2;
      border: 1px solid #fecaca;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 16px;
    }
    .url-info {
      background: #f3f4f6;
      padding: 8px;
      border-radius: 4px;
      margin-bottom: 16px;
      font-size: 0.875rem;
      color: #6b7280;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const container = document.querySelector('.star');
      if (!container) return;
      const inputs = container.querySelectorAll('input[type=radio]');
      const labels = container.querySelectorAll('label');
      
      function paint(val) {
        labels.forEach(l => {
          const labelValue = Number(l.dataset.value);
          if (labelValue <= val) {
            l.classList.add('selected');
          } else {
            l.classList.remove('selected');
          }
        });
      }
      
      // 初期状態では全て白星
      paint(0);
      
      inputs.forEach(input => {
        input.addEventListener('change', () => {
          paint(Number(input.value));
        });
      });
      
      // 既に選択されている値がある場合は適用
      const checked = container.querySelector('input:checked');
      if (checked) {
        paint(Number(checked.value));
      }
    });
  </script>
</head>
<body>
  <h1>お問い合わせ（{{ $application->name }}）</h1>
  <div class="url-info">
    このフォームは <code>/contact/{{ $application->slug }}</code> からアクセスできます。
  </div>

    @if ($errors->any())
      <div class="error-box">
        <strong style="color: #dc2626;">入力内容にエラーがあります。</strong>
        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
          @foreach ($errors->all() as $e)
            <li style="color: #dc2626;">{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('contacts.store', ['app' => $application->slug]) }}">
      @csrf

      <div class="form-group">
        <label for="name">お名前（任意）</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="お名前を入力してください">
        @error('name')<div class="error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="category">カテゴリ（必須）</label>
        <select id="category" name="category" required>
          <option value="">選択してください</option>
          @foreach($categories as $value => $label)
            <option value="{{ $value }}" @selected(old('category', $selectedCategory ?: 'bug') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('category')<div class="error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="title">タイトル（任意）</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="タイトルを入力してください">
        @error('title')<div class="error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>評価（任意：☆1–5）</label>
        <div class="star" aria-label="評価">
          @for($i=1; $i<=5; $i++)
            <input id="rating-{{ $i }}" type="radio" name="rating" value="{{ $i }}" @checked(old('rating') == $i)>
            <label for="rating-{{ $i }}" data-value="{{ $i }}"></label>
          @endfor
        </div>
        @error('rating')<div class="error">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label for="detail">詳細（必須）</label>
        <textarea id="detail" name="detail" required placeholder="お問い合わせ内容を詳しく入力してください">{{ old('detail') }}</textarea>
        @error('detail')<div class="error">{{ $message }}</div>@enderror
      </div>

      <button type="submit">送信する</button>
    </form>
</body>
</html>
