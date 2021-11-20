<!DOCTYPE html>

<html lang="ja">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Atte</title>
</head>

<style>
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
}

.service_png-position {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  width: 100%;
}

.service_png-positiondiv {
  width: 48%;
}

.form{
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
}
</style>
<body>
  
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
            <form method="POST" action="{{ route('logout') }}" name='$name' value='$name' >
              @csrf
              <button type="submit" class="header__nav-list-link">ログアウト</button>
            <!-- <a href="{{ route('logout') }}" class="header__nav-list-link" style="color:black;">ログアウト</a> -->
            </form>
          </li>
        </ul>
      </nav>
    </header>
  <div class="service">
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


