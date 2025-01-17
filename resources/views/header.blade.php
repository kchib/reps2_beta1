<div class="header-wrapper">
    <div class="logo-wrapper">
        <a href="/"><img src="{{route('home')}}/images/logo.png" class="logo-header" alt=""></a>
    </div>
    <div>
        @include('layouts.partials.search')
    </div>
    @if(!Auth::user())
        <!--IF user is NOT logged,  displays this view-->
        <div class="no-logged-user">
            @if(Route::currentRouteName() !== 'registration_form'))
                <a href="{{route('registration_form')}}" class="registration-link">
                    <img src="{{route('home')}}/images/icons/vector.png" alt="">
                    <span>Регистрация</span>
                </a>
                <!-- LOGIN FORM-->
                @include('auth.login-form')
                <!-- END LOGIN FORM-->
                <a href="{{route('password.request')}}" class="password-repairing">
                    <img src="{{route('home')}}/images/icons/lock_icon.png" alt="">
                    Восстановление пароля
                </a>
            @endif
        </div>
    @else
    <!--IF user is logged, displays this view-->
        <div class="logged-user">
            <div class="logged-user-info">
                <a href="{{route('user.messages_all')}}" class="user-new-messages">
                    <img src="{{route('home')}}/images/icons/new_message.png" alt="">
                    @if($general_helper->getNewUserMessage() != 0)
                        <div class="user-new-messages-qty">{{$general_helper->getNewUserMessage()}}</div>
                    @endif
                </a>

                @if(Auth::user()->view_avatars == 1)
                    @if(Auth::user()->avatar)
                        <a href="{{route('user_profile',['id' =>Auth::id()])}}" class="logged-user-avatar">
                            <img src="{{Auth::user()->avatar->link}}" alt="">
                        </a>
                    @else
                        <a href="{{route('user_profile',['id' =>Auth::id()])}}" class="logged-user-avatar">A</a>
                    @endif
                @endif
                <a href="{{route('user_profile',['id' =>Auth::id()])}}" class="logged-user-nickname">{{Auth::user()->name}}</a>
            </div>
        @if(Auth::user()->user_role_id == 1)
            <!--IF user is admin-->
                <a href="{{route('admin.home')}}" class="btn-blue admin-button">
                    <img src="{{route('home')}}/images/icons/admin_icon.png" class="margin-right-5" alt="">
                    <span>Admin Panel</span>
                </a>
                <!--END IF user is admin-->
            @endif
            <div class="logged-user-action-bar">
                <div>
                    <a href="{{route('user.friends_list')}}" class="logged-user-friends" title="Мои друзья"></a>
                    <a href="{{route('user.messages_all')}}" class="logged-user-messages" title="Мои сообщения"></a>
                    <a href="" class="logged-user-menu" title="Меню"></a>
                    <a href="{{route('logout',['id' =>Auth::id()])}}" class="logged-user-logout" title="Выйти"></a>
                </div>
                <div class="logged-user-menu-links">
                    <ul>
                        <li>
                            <a href="{{route('user_profile',['id' =>Auth::id()])}}">Мой аккаунт</a>
                        </li>
                        <li>
                            <a href="{{route('edit_profile')}}">Настройки</a>
                        </li>
                        <li>
                            <a href="{{route('gallery.list_user', ['id' => Auth::id()])}}">Галерея</a>
                        </li>
                        <li>
                            <a href="{{route('user.get_rating', ['id' => Auth::id()])}}">Репутация</a>
                        </li>
                        <li>
                            <a href="{{route('user.comments')}}">Мои посты</a>
                        </li>
                        <li>
                            <a href="{{route('forum.topic.my_list')}}">Мои темы</a>
                        </li>
                        <li>
                            <a href="{{route('replay.create')}}">Отправить реплей</a>
                        </li>
                        <li>
                            <a href="{{route('replay.my_user')}}">Мои реплеи</a>
                        </li>
                        <li>
                            <a href="{{route('replay.my_gosu')}}">Мои Госу реплеи</a>
                        </li>
                        <li>
                            <a href="{{route('user.ignore_list')}}">Игнор лист</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--IF user is logged -->
    @endif
</div>