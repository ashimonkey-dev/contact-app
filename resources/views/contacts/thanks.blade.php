<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>送信完了｜{{ $application->name }}</title>
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
      color: #059669;
      font-size: 1.5rem;
      margin-bottom: 16px;
      text-align: center;
    }
    .success-icon {
      font-size: 3rem;
      color: #059669;
      margin-bottom: 16px;
      text-align: center;
    }
    .info-box {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      padding: 16px;
      border-radius: 4px;
      margin: 16px 0;
    }
    .back-button {
      width: 100%;
      display: block;
      background-color: #3b82f6;
      color: white;
      padding: 16px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      margin-top: 16px;
      text-align: center;
      box-sizing: border-box;
    }
    .back-button:hover {
      background-color: #2563eb;
    }
    .detail-content {
      background: #f8f9fa;
      padding: 12px;
      border-radius: 4px;
      margin-top: 8px;
      white-space: pre-line;
      line-height: 1.6;
    }
    .message {
      color: #6b7280;
      text-align: center;
      margin: 16px 0;
    }
  </style>
</head>
<body>
  <div class="success-icon">✅</div>
  <h1>送信ありがとうございました</h1>
  
  <div class="info-box">
    <h3 style="margin-top: 0; color: #374151;">送信内容</h3>
    <p><strong>アプリ：</strong>{{ $application->name }}</p>
    @if($contact->name)
      <p><strong>お名前：</strong>{{ $contact->name }}</p>
    @endif
    <p><strong>カテゴリ：</strong>
      @switch($contact->category)
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
          {{ $contact->category }}
      @endswitch
    </p>
    @if($contact->title)
      <p><strong>タイトル：</strong>{{ $contact->title }}</p>
    @endif
    @if($contact->rating)
      <p><strong>評価：</strong>{{ str_repeat('★', $contact->rating) }}{{ str_repeat('☆', 5 - $contact->rating) }} ({{ $contact->rating }}/5)</p>
    @endif
    <div style="margin-top: 10px;">
      <strong>詳細：</strong>
      <div class="detail-content">{{ $contact->detail }}</div>
    </div>
    <p style="margin-top: 15px;"><strong>送信日時：</strong>{{ $contact->created_at->format('Y年m月d日 H:i') }}</p>
  </div>
  
  <div class="message">
    お問い合わせ内容を確認いたしました。<br>回答が必要な場合は、後日ご連絡いたします。
  </div>
  
  <a href="{{ route('contacts.form', ['app' => $application->slug]) }}" class="back-button">📝 もう一度送信する</a>
</body>
</html>
