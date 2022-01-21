<x-app-layout>
  <style>
    .pagination {
      justify-content: center;
    }

    img {
      width: 100%;
      height: auto;
    }

    a {
      color: #000000;
      font-weight: bold;
      font-size: 100%;
      text-decoration: none;
    }

    li {
      list-style: none;
    }

    body {
      color: #000000;
      line-height: 1.7;
    }

    .flex__item {
      display: flex;
      justify-content: space-between;
    }

    /*--------------------- header--------------------- */

    .header {
      position: sticky;
      top: 0px;
      z-index: 1;
      height: 70px;
      background-color: #fff;
      padding: 20px 0;
    }

    .header-title {
      font-size: 200%;
      padding: 0px 35px;
    }

    .header__nav {
      margin-right: 60px;
    }

    .header__nav-list li {
      margin-right: 50px;
      font-size: 120%;
    }

    .header__nav-list li:last-child {
      margin-right: 0;
    }

    .header__nav-list-link {
      height: 70px;
      display: inline-block;
    }

    .header__nav-list-link:hover {
      filter: opacity(70%);
      cursor: pointer;
    }

    .header__nav-list-link1 {
      height: 70px;
      display: inline-block;
      font-size: 18px;
      font-weight: bold;
      padding-bottom: 38px;
      background: none;
      border: none;
      outline: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }

    .header__nav-list-link1:hover {
      filter: opacity(70%);
      cursor: pointer;
    }


    /*--------------------- stamp--------------------- */
    .service {
      padding: 20px 160px 100px;
      background-color: #f1eeee;
    }

    .service-title {
      font-size: 22px;
      text-align: center;
      margin-bottom: 30px;
      font-weight: bolder;
      padding: 20px;
    }

    .service_png-position {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      width: 100%;
    }

    .service_png-positiondiv {
      width: 100%;
    }

    .form {
      margin: 20px auto;
    }

    .form-item {
      padding: 5px 0;
      width: 100%;
      display: flex;
      align-items: center;
    }

    .form-btn {
      border-radius: 6px;
      border: 0px solid #fff;
      background-color: #fff;
      height: 200px;
      width: 100%;
      display: block;
      font-size: 22px;
      margin: 0px 10px;
      font-weight: bolder;
    }

    .form-btn:hover {
      filter: opacity(70%);
      cursor: pointer;
    }

    .service-title2 {
      font-size: 16px;
      text-align: center;
      font-weight: bolder;
      padding: 10px;
    }

    .form-item1 {
      width: 100%;
      margin: 0 auto;
      padding: auto;
      font-size: 18px;
      border-bottom: 1px solid black;
      border-top: 1px solid black;
      text-align: center;
      /* 線がつく */
      border-collapse: collapse;
    }

    th,
    td {
      width: 16.6%;
    }

    tr {
      border-bottom: 1px solid black;
    }

    .form-btn3 {
      border: 1px solid blue;
      background-color: #fff;
      height: 30px;
      width: 40px;
      display: block;
      font-size: 22px;
      font-weight: bolder;
      color: blue;
      padding-bottom: 35px;
      margin-left: 300px;
    }

    .form-btn3:hover {
      filter: opacity(70%);
      cursor: pointer;
    }

    .form-btn4 {
      border: 1px solid blue;
      background-color: #fff;
      height: 30px;
      width: 40px;
      display: block;
      font-size: 22px;
      font-weight: bolder;
      color: blue;
      padding-bottom: 35px;
      margin-right: 300px;
    }

    .form-btn4:hover {
      filter: opacity(70%);
      cursor: pointer;
    }

    .service-title1 {
      display: flex;
      justify-content: center;
    }
  </style>


  <header class="header flex__item">
    <a class="header__nav-list-link header-title">Atte</a>
    <nav class="header__nav">
      <ul class="header__nav-list flex__item">
        <li>
          <a href="/" class="header__nav-list-link" style="color:black;">ホーム</a>
        </li>
        <li>
          <a href="/attendance" class="header__nav-list-link" style="color:black;">日付一覧</a>
        </li>
        <li>
          <a href="/user" class="header__nav-list-link" style="color:black;">ユーザーページ</a>
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
  <div class="service">
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
    <div class="service_png-position">
      <div class="service_png-positiondiv">
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
</x-app-layout>