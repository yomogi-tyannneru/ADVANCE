<!DOCTYPE html>

<html lang="ja">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Standardlasttest</title>
<style>
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
</head>

<body>
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