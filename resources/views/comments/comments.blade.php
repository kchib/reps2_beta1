@section('css')
    <!--SCEditor -  WYSIWYG BBCode editor -->
    <link rel="stylesheet" href="{{route('home')}}/js/sceditor/minified/themes/default.min.css"/>
@endsection

<!--Comments-->
@if($comments->total() > 0)
    <!-- COMMENTS PAGINATION TOP-->
    <div class="pagination-content"></div>
    <!-- END COMMENTS PAGINATION TOP-->

    <!-- COMMENTS CONTENT -->
    <div id="ajax_section_comments" data-pages="" data-comments-total="{{$comments->total()}}">
        <div class="load-wrapp">
            <img src="/images/loader.gif" alt="">
        </div>
    </div>
    <!-- END COMMENTS CONTENT -->

    <!-- COMMENTS PAGINATION BOTTOM -->
    <div class="pagination-content"></div>
    <!-- END COMMENTS PAGINATION BOTTOM-->
@else
    <div class="content-box">
        <div class="col-md-12 section-title">
            <div>Комментарии:</div>
        </div>
        <div class="col-md-12 comment-content-wrapper">
            <div class="comment-content">
                Комментарии отсутствуют
            </div>
        </div>
    </div>
@endif
<!--END Comments-->

@section('js')
    <!--SCEditor -  WYSIWYG BBCode editor -->
    <script src="{{route('home')}}/js/sceditor/minified/jquery.sceditor.min.js"></script>

    <script src="{{route('home')}}/js/sceditor/minified/jquery.sceditor.xhtml.min.js"></script>
    <script src="{{route('home')}}/js/sceditor/languages/ru.js"></script>
    <script>
        $(function () {
            getSections(1);
            $('.pagination-content').on('click', '.page-link', function (e) {
                e.preventDefault();
                $('.load-wrapp').show();
                var page = $(this).attr('data-page');
                getSections(page);
            });
        });

        function getSections(page) {
            var container = $('#ajax_section_comments');
            var comments_total = container.attr('data-comments-total');
            var comments_on_page = 20;
            $.get('{{route('comments.pagination',['object' => $object, 'id' => $id])}}' +
                '?page=' + page, {}, function (data) {
                container.html(data.comments);
                $('.pagination-content').html(data.pagination);
                $('.load-wrapp').hide();

                /**move to top of comments*/
                if (page !== 1) {
                    moveToTop(container);
                }
                /***/
                writeCommentIds(comments_total, page, comments_on_page);
            })
        }

        /**
         * Comments box is the same for all pages
         *SCEditor -  WYSIWYG BBCode editor
         * https://www.sceditor.com/
         * */
        $(function () {
            /**custom commands for HTML text editor*/
            addCountries();
            addRaces();

            if ($('body').find('#comment-content').length > 0) {
                var textarea = document.getElementById('comment-content');

                sceditor.create(textarea, {
                    format: 'xhtml',
                    style: '{{route('home')}}' + '/js/sceditor/minified/themes/content/default.min.css',
                    emoticonsRoot: '{{route('home')}}' + '/js/sceditor/',
                    locale: 'ru',
                    toolbar: 'bold,italic,underline|' +
                    'left,center,right,justify|' +
                    'font,size,color,removeformat|' +
                    'source,quote,code|' +
                    'image,link,unlink|' +
                    'emoticon|' +
                    'date,time|' +
                    'countries|'+
                    'races',
                    emoticons: {
                        // Emoticons to be included in the dropdown
                        dropdown: getAllSmiles(),
                        // Emoticons to be included in the more section
                        more: getMoreSmiles()
                    }
                });
            }
            /**add quote*/
            $('body').on('click', '.quote img', function () {
                addText(textarea, $(this));
            });
        });

        /**
         * Add quote into comment form
         * */
        function addText(textarea, quote_data) {
            var selection = window.getSelection();
            var quoted_user = quote_data.attr('data-user');
            var comment_id = quote_data.attr('data-id');
            var object = '{{$object}}';
            var object_id = '{{$id}}';

            /**create comment url string*/
            var url = createCommentUrl(comment_id, object, object_id);
            /**crete user info sring*/
            var user = '[u]' + quoted_user + ':[/u]';
            /**create quote string*/
            var quote = '[quote]' + selection.toString() + '[/quote]';
            /**create full quote text*/
            var text = url + user + quote;

            /**add text*/
            sceditor.instance(textarea).insert(text);
            sceditor.instance(textarea).focus();
            moveToTop($('.comment-form-wrapper'));
        }

        function createCommentUrl(comment_id, object, object_id) {
            var url = '[url={{route('home')}}/';

            if (object === 'topic') {
                url += 'forum/';
            }
            if(object === 'gallery'){
                url += object + '/photo/' + object_id;
            }else{
                url += object + '/' + object_id;
            }

            if (comment_id === undefined || comment_id === '') {
                url += '] >>';
            } else {
                url += '#' + comment_id + ']#' + comment_id;
            }
            url += ' [/url]';
            return url;
        }

        /**Write comments IDs on page*/
        function writeCommentIds(comments_total, current_page, comments_on_page) {
            var i = 0;
            var j = 0;
            /**ID of first comment on page*/
            var first_comment = comments_total;
            if (parseInt(current_page) !== 1) {
                first_comment = (comments_total - (comments_on_page * current_page - comments_on_page));
            }

            $('.comment-id').each(function () {
                $(this).attr('id', first_comment - i);
                $(this).html('#' + (first_comment - i));
                i++;
            });

            $('.quote img').each(function () {
                $(this).attr('data-id', first_comment - j);
                j++;
            });
        }
    </script>
@endsection