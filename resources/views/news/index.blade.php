@extends('layouts.site')
@inject('general_helper', 'App\Services\GeneralViewHelper')
@section('sidebar-left')
    <!-- All Forum Topics -->
    @include('sidebar-widgets.all-forum-sections')
    <!-- END All Forum Topics -->
@endsection

@section('content')

    <!-- Breadcrumbs -->
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="/">Главная</a>
                </li>
                <li>
                    <a href="{{route('forum.index')}}">/ Форум</a>
                </li>
                <li>
                    <a href="#" class="active">/ Новости</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END Breadcrumbs -->

    <!--  CONTENT -->
    <div id="ajax_section_news" data-path="{{route('news.pagination').'?'.$request}}">
        <div class="load-wrapp">
            <img src="/images/loader.gif" alt="">
        </div>
    </div>
    <!-- END CONTENT -->

    <!--  PAGINATION -->
    <div class="pagination-content"></div>
    <!-- END  PAGINATION -->
@endsection

@section('sidebar-right')
    <!--Banners-->
    @include('sidebar-widgets.banner')
    <!-- END Banners -->

    <!-- New Users-->
    @include('sidebar-widgets.new-users')
    <!-- END New Users-->

    <!-- User's Replays-->
    @include('sidebar-widgets.users-replays')
    <!-- END User's Replays-->

    <!-- Gallery -->
    @include('sidebar-widgets.random-gallery')
    <!-- END Gallery -->
@endsection

@section('js')
    <script>
        $(function () {
            getSections(1);
            $('.pagination-content').on('click', '.page-link', function (e) {
                e.preventDefault();
                $('.load-wrapp').show();
                var page = $(this).attr('data-page');
                getSections(page);
            })
        });
        function getSections(page) {
            var container = $('#ajax_section_news');
            var body = $("html, body");
            var path = container.attr('data-path');
            $.get(path +'&page='+page, {}, function (data) {
                container.html(data.news);
                $('.pagination-content').html(data.pagination);
                $('.load-wrapp').hide();

                /**move to top of page*/
                moveToTop(body);
            });
        }
    </script>
@endsection

