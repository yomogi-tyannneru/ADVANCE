<x-app-layout>
  <body>
    <header class="header flex__item">
      <a class="header__nav-list-link header-title">Atte</a>
      <nav class="header__nav">
        <ul class="header__nav-list flex__item">
          <li>
            <a href="/" class="header__nav-list-link">ホーム</a>
          </li>
          <li>
            <a href="/attendance" class="header__nav-list-link">日付一覧</a>
          </li>
          <li>
            <a href="/user" class="header__nav-list-link">ユーザーページ</a>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" name='$name' value='$name'>
              @csrf
              <button type="submit" class="header__nav-list-link1">ログアウト</button>
            </form>
          </li>
        </ul>
      </nav>
    </header>
    <div class="service1">
      <p class="service-title">{{ $user->name }} さんお疲れさまです！</p>
      @if (session('error_message'))
      <p class="service-title" style="color: red;">{{ session('error_message') }}</p>
      @endif
      @if (session('success_message'))
      <p class="service-title" style="color: green;">{{ session('success_message') }}</p>
      @endif
      <div class="service_png-position">
        <div class="service_png-positiondiv">
          <form action="{{ route('punch_in') }}" class="form" name="punch_in" method="POST">
            @csrf
            <div class="form-item">
              <input type="submit" name="punch_in" value="勤務開始" class="form-btn">
              <input type="hidden" name="id" value="punch_in" />
            </div>
          </form>
        </div>
        <div class="service_png-positiondiv">
          <form action="{{ route('punch_out') }}" class="form" name="punch_out" method="POST">
            @csrf
            <div class="form-item">
              <input type="submit" name="punch_out" value="勤務終了" class="form-btn">
            </div>
          </form>
        </div>
        <div class="service_png-positiondiv">
          <form action="{{ route('break_start') }}" class="form" name="break_start" method="POST">
            @csrf
            <div class="form-item">
              <input type="submit" name="break_start" value="休憩開始" class="form-btn">
            </div>
          </form>
        </div>
        <div class="service_png-positiondiv">
          <form action="{{ route('break_end') }}" class="form" name="break_end" method="POST">
            @csrf
            <div class="form-item">
              <input type="submit" name="break_end" value="休憩終了" class="form-btn">
            </div>
          </form>
        </div>
      </div>
    </div>
    <p class="service-title2">Atte,inc.</p>
  </body>
</x-app-layout>