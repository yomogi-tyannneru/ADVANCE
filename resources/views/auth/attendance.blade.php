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
  line-height: 70px;
  background-color: #fff;
}

.header-title {
  font-size: 180%;
  padding: 0px 40px;
}

.header__nav {
  margin-right: 40px;
}

.header__nav-list li {
  margin-right: 25px;
  font-size: 100%;
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