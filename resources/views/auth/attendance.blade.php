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
    <div class="service2">
      <div class="service-title1">

        <form action="{{ route('attendance_beforedate') }}" class="form" method="GET">
          @csrf
          <input type="submit" class="form-btn3" value="<">
          <input type="hidden" value="{{ $today  }}" name="date">
        </form>
        <p class="service-title">{{ $today }}</p>
        <form action="{{ route('attendance_nextdate') }}" class="form" method="GET">
          @csrf
          <input type="submit" class="form-btn4" value=">">
          <input type="hidden" value="{{ $today }}" name="date">
        </form>
      </div>
      <div class="service_png-position2">
        <div class="service_png-position2div">
          <div class="form-item">
            <table class="form-item1">
              <thead>
                <tr>
                  <th class="form-item3">名前</th>
                  <th>勤務開始</th>
                  <th>勤務終了</th>
                  <th>休憩時間</th>
                  <th>勤務時間</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($times_data as $data)
                <tr>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->punch_in }}</td>
                  <td>{{ $data->punch_out ?? '--:--:--' }}</td>
                  <td>{{ array_key_exists($data->id, $rest_data) ? $rest_data[$data->id] : '--:--:--' }}</td>
                  <td>{{ $data->work_time ?? '--:--:--' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if (empty($times_data) || empty($times_data->items()))
          <p class="service-title" style="color: red;">{{ '打刻データがありません' }}</p>
          @else
          {{ $times_data->appends(request()->query())->links()}}
          @endif
        </div>
      </div>
    </div>
    <p class="service-title2">Atte,inc.</p>
  </body>
</x-app-layout>