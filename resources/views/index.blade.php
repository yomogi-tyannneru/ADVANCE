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






h1 {
  text-align: center;
}
.form{
  width: 60%;
  margin: 20px auto;
}
.form-item {
  padding: 5px 0;
  width: 100%;
  display: flex;
  align-items: center;
}
.form-item-label {
  width: 20%;
  font-weight: bold;
  font-size: 16px;
}
.form-item-label1 {
  padding-left: 40px;
}
.form-item-label-required {
  color: red;
}
.form-item-input {
  border: 1px solid #C0C0C0;
  border-radius: 6px;
  margin-left: 40px;
  padding: 0 10px;
  height: 48px;
  width: 100%;
  font-size: 18px;
}
.form-item-input1 {
  border: 1px solid #C0C0C0;
  border-radius: 6px;
  margin-left: 40px;
  padding: 0 10px;
  height: 48px;
  width: 45%;
  font-size: 18px;
}
.form-item-label-example {
  padding-left: 200px;
  color: #C0C0C0;
}
.form-item-label-example1 {
  padding-left: 280px;
  color: #C0C0C0;
}
label {
  position: relative;
  cursor: pointer;
  padding-left: 50px;
}

label::before,
label::after {
  content: "";
  display: block; 
  border-radius: 50%;
  position: absolute;
  transform: translateY(-50%);
  top: 50%;
}

label::before {
  background-color: #fff;
  border: 1px solid #C0C0C0;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  left: 5px;
}

label::after {
  background-color: #C0C0C0;
  border-radius: 50%;
  opacity: 0;
  width: 16px;
  height: 16px;
  left: 13px
}

input:checked + label::after {
  opacity: 1;
}

.visually-hidden {
 position: absolute;
 white-space: nowrap;
 border: 0;
 clip: rect(0 0 0 0);
 clip-path: inset(50%);
 overflow: hidden;
 height: 1px;
 width: 1px;
 margin: -1px;
 padding: 0;
}
.male {
  padding-right: 30px;
}
.form-item-input2 {
  border: 1px solid #C0C0C0;
  border-radius: 6px;
  margin-left: 40px;
  padding: 0 10px;
  height: 48px;
  width: 80%;
  font-size: 18px;
}
.form-item-textarea {
  border: 1px solid #C0C0C0;
  border-radius: 6px;
  margin-left: 40px;
  padding: 0 10px;
  height: 200px;
  width: 100%;
  font-size: 18px;
}
.form-btn {
  border-radius: 6px;
  margin: 32px auto 0;
  padding-top: 20px;
  padding-bottom: 20px;
  width: 280px;
  display: block;
  background: black;
  color: #fff;
  font-weight: bold;
  font-size: 20px;
  border: none;
  cursor: pointer;
}
</style>


<body>
  <header class="header flex__item">
    <a>Atte</a>
    <nav class="header__nav">
      <div class="header__nav-list flex__item">
        <form action="/confirmation" class="form" name="contact" method="POST">
          @csrf
          <li>
            <a href="index.html" class="header__nav-list-link" style="color:black;">ホーム</a>
          </li>
          <li>
            <a href="service.html" class="header__nav-list-link" style="color:black;">日付一覧</a>
          </li>
          <li>
            <a href="news.html" class="header__nav-list-link" style="color:black;">ログアウト</a>
          </li>
        </div>
      </nav>
    </header>
  <h1>お問い合わせ</h1>
<form action="/confirmation" class="form" name="contact" method="POST">
@csrf
  <div class="form-item">
    <p class="form-item-label">
      お名前<span class="form-item-label-required">※</span>
    </p>
    <input type="text" name="fullname" class="form-item-input1">
    <input type="text" name="fullname" class="form-item-input1">
  </div>
  <p class="form-item-label-example">例）山田　　　　　　　　　　　　　　　　　　　例）太郎</p>
  <div class="form-item">
    <p class="form-item-label">
      性別<span class="form-item-label-required">※</span>
    </p>
    <input  class="visually-hidden" type="radio" name="gender" id="male" checked="checked" />
    <label for="male" class="male">男性</label>
    <input class="visually-hidden" type="radio" name="gender" id="female"/>
    <label for="female">女性</label>
  </div>
  <div class="form-item">
    <p class="form-item-label">
      メールアドレス<span class="form-item-label-required">※</span>
    </p>
    
    <input type="text" name="email" class="form-item-input">
    
  </div>
  <p class="form-item-label-example">例）test@example.com</p>
  <div class="form-item">
    <p class="form-item-label">
      郵便番号<span class="form-item-label-required">※</span>
    </p>
    <p class="form-item-label1">〒</p>
    <input type="text" name="postcode" class="form-item-input2">
  </div>
  <p class="form-item-label-example1">例）123-4567</p>
  <div class="form-item">
    <p class="form-item-label">
      住所<span class="form-item-label-required">※</span>
    </p>
    <input type="text" name="address" class="form-item-input">
  </div>
  <p class="form-item-label-example">例）東京都渋谷区千駄ヶ谷1-2-3</p>
  <div class="form-item">
    <p class="form-item-label">建物名</p>
    <input type="text" name="building_name" class="form-item-input">
  </div>
  <p class="form-item-label-example">例）千駄ヶ谷マンション101</p>
  <div class="form-item">
    <p class="form-item-label">
      ご意見<span class="form-item-label-required">※</span>
    </p>
    <textarea name="opinion" maxlength="120" class="form-item-textarea"></textarea>
  </div>
  <input type="submit" class="form-btn" value="確認" name="opinion1" >
</form>
</body>