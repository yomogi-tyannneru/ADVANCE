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
  width: 100%;
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
.form-item1 {
    width:100%; 
    margin: 0 auto; 
    padding: auto;
    font-size: 18px;
    border-bottom: 1px solid black;
    border-top: 1px solid black;
    border-collapse: collapse;
    text-align: center;
  }
  th,td{
    width: 16.6%;
  }
  tr {
    border-bottom: 1px solid black;
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
          <a href="news.html" class="header__nav-list-link" style="color:black;">ログアウト</a>
        </li>
      </ul>
    </nav>
  </header>
  <div class="service">
    <p class="service-title">日時ページネーション</p>
    <div class="service_png-position">
      <div class="service_png-positiondiv">
        <form action="/" class="form" name="punch_in" method="POST">
          @csrf
          <div class="form-item">
            <table class="form-item1">
              <thead>
                <tr>
                  <th class="form-item3">名前</th>
                  <th>日付</th>
                  <th>勤務開始</th>
                  <th>勤務終了</th>
                  <th>休憩時間</th>
                  <th>勤務時間</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($times_data as $data)
                  <tr>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['date'] }}</td>
                    <td>{{ $data['punch_in'] }}</td>
                    <td>{{ $data['punch_out'] ?? '--:--:--' }}</td>
                    <td>{{ $data['rest_time'] }}</td>
                    <td>{{ $data['work_time'] ?? '--:--:--' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
        <div class="container">
          @foreach ($users as $user)
          {{ $user->name }}
          {{ $date->date }}
          @endforeach
        </div>
        {{ $users->links() }}
      </div>
    </div>
  </div>
  <p class="service-title2">Atte,inc.</p>
</body>