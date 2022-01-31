<x-app-layout>
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
  <div class="service4">
    <p class="service-title">ユーザー一覧</p>
    <div class="service_png-position2">
      <div class="service_png-position2div">
        <div class="form-item">
          <table class="form-item1">
            <thead>
              <tr>
                <th class="form-item3">名前</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td><a href="{{ route('user.show', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $users->links() }}
      </div>
    </div>
  </div>
  <p class="service-title2">Atte,inc.</p>
</x-app-layout>