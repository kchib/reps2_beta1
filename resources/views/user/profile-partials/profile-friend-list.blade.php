<div class="content-box user-account-widget">
    <div class="user-account-widget-title">Список друзей</div>
    <div class="widget-new-user">
        @if(count($friends) > 0)
            @foreach($friends as $friend)
                <a href="{{route('user_profile',['id' => $friend->id])}}">
                    @if($friend->country_id)
                        <span class="flag-icon flag-icon-{{mb_strtolower($countries[$friend->country_id]->code)}}"></span>
                    @else
                        <span>NO</span>
                    @endif
                    <span>{{$friend->name}}</span>
                </a>
            @endforeach
        @else
            <p class="text-center">Список пуст</p>
        @endif
    </div>
</div><!-- close div /.content-box -->