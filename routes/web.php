<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'activity'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/last_forums', 'HomeController@lastForums')->name('home.last_forum');

    Route::group(['prefix' => 'widget'], function () {
        Route::get('/all_forum_sections', 'WidgetController@allForumSections')->name('widgets.all_forum_sections');
        Route::get('/general_forum_sections',
            'WidgetController@generalForumSections')->name('widgets.general_forum_sections');
        Route::get('/gosu_replays', 'WidgetController@gosuReplay')->name('widgets.gosu_replays');
    });

    Route::get('error/{error}', 'ErrorController@index')->name('error');
    Route::get('/comments/{object}/{id}', 'CommentController@pagination')->name('comments.pagination');
    Route::get('/search', 'HomeController@search')->name('home.search');
    Route::get('/email/verified/{token}', 'Auth\RegisterController@emailVerified')->name('email_verified');
    Route::middleware(['guest'])->group(function () {
        Route::post('/login', 'Auth\LoginController@userLogin')->name('login');

        Route::group(['prefix' => 'password'], function () {
            Route::get('/update', 'Auth\ResetPasswordController@updateOldPassword')->name('get_update_password');
            Route::get('/new/{token}', 'Auth\ResetPasswordController@viewNewPassword')->name('update_old_password');
            Route::post('/new', 'Auth\ResetPasswordController@saveNewPassword')->name('save_new_password');
            Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
        });
    });

    /**news page*/
    Route::get('news', 'NewsController@index')->name('news');
    Route::get('news/pagination', 'NewsController@pagination')->name('news.pagination');

    /**sidebar voting*/
    Route::post('question/{id}/set_answer', 'InterviewQuestionController@setAnswer')->name('question.set_answer');
    Route::post('question/{id}/view_answer', 'InterviewQuestionController@getResult')->name('question.view_answer');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        /**comments rating: like/dislike*/
        Route::post('comment/{id}/set_rating', 'CommentsRatingController@setRating')->name('comment.set_rating');
        Route::get('comment/{id}/get_rating', 'CommentsRatingController@getRating')->name('comment.ger_rating');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('registration_form');
        Route::post('/register', 'Auth\RegisterController@register')->name('registration');
        Route::get('{id}/friends_list', 'UserFriendController@getFriendsList')->name('user.friends_list.by_id');

        Route::group(['middleware' => 'auth'], function () {
            Route::get('/comments', 'UsersCommentController@index')->name('user.comments');
            Route::get('/comments/pagination', 'UsersCommentController@pagination')->name('user.comments.pagination');

            Route::get('{id}/ignore', 'IgnoreController@setIgnore')->name('user.set_ignore');
            Route::get('{id}/not_ignore', 'IgnoreController@setNotIgnore')->name('user.set_not_ignore');
            Route::get('/ignore_list', 'IgnoreController@getIgnoreList')->name('user.ignore_list');
            Route::get('{id}/add_friend', 'UserFriendController@addFriend')->name('user.add_friend');
            Route::get('{id}/remove_friend', 'UserFriendController@removeFriend')->name('user.remove_friend');
            Route::get('/friends_list', 'UserFriendController@getFriendsList')->name('user.friends_list');
            Route::get('/edit', 'UserController@edit')->name('edit_profile');
            Route::post('/save', 'UserController@update')->name('save_profile');

            Route::post('messages/{id}/update', 'UserMessagingController@updateMessage')->name('user.message.update');
            Route::post('messages/{id}/delete', 'UserMessagingController@removeMessage')->name('user.message.delete');
            Route::get('{id}/messages', 'UserMessagingController@getUser')->name('user.messages');
            Route::get('messages', 'UserMessagingController@getUser')->name('user.messages_all');
            Route::get('/message/{dialog_id}/load', 'UserMessagingController@load')->name('user.message_load');
            Route::post('/message/{dialog_id}/send', 'UserMessagingController@send')->name('user.message.send');
        });

        Route::get('/{id}', 'UserController@show')->name('user_profile');

        /**get user reputation list*/
        Route::get('{id}/get_rating', 'RatingController@getRatingUser')->name('user.get_rating');
        Route::get('{id}/pagination', 'RatingController@userRatingPagination')->name('user.paginate');

        Route::get('{id}/replay', 'ReplayUsersController@getUserReplay')->name('user.user_replay');
        Route::get('{id}/gosu_replay', 'ReplayGosuController@getUserReplay')->name('user.gosu_replay');
        Route::get('{id}/all_replay', 'ReplayController@getAllUserReplay')->name('user.all_replay');
        Route::get('{id}/topic', 'ForumTopicController@getUserTopic')->name('user.forum_topic');
        Route::get('{id}/topic/pagination',
            'ForumTopicController@userTopicPagination')->name('user.forum_topic.pagination');

        Route::get('{id}/comments', 'UsersCommentController@index')->name('user.user_comments');
        Route::get('{id}/comments/pagination', 'UsersCommentController@pagination')->name('user.user_comments.pagination');

    });

    Route::group(['prefix' => 'forum'], function () {
        Route::get('/', 'ForumController@index')->name('forum.index');
        Route::group(['prefix' => 'section'], function () {
            Route::get('/{name}', 'ForumController@section')->name('forum.section.index');
            Route::get('/{name}/pagination', 'ForumController@sectionPagination')->name('forum.section.pagination');
        });
        Route::group(['prefix' => 'topic'], function () {
            Route::get('search', 'ForumController@searchTopic')->name('forum.topic.search');
            Route::get('search/pagination',
                'ForumController@searchPaginationTopic')->name('forum.topic.search.pagination');

            /**reputation forum topic list*/
            Route::get('{id}/get_rating', 'TopicRatingController@getRating')->name('forum.topic.get_rating');
            Route::get('{id}/paginate', 'TopicRatingController@paginate')->name('forum.topic.paginate');

            Route::group(['middleware' => 'auth'], function () {
                Route::get('/my', 'ForumTopicController@getUserTopic')->name('forum.topic.my_list');
                Route::get('/create', 'ForumTopicController@create')->name('forum.topic.create');
                Route::post('/store', 'ForumTopicController@store')->name('forum.topic.store');
                Route::get('{id}/delete', 'ForumTopicController@destroy')->name('forum.topic.delete');
                Route::get('{id}/edit', 'ForumTopicController@edit')->name('forum.topic.edit');
                Route::post('{id}/update', 'ForumTopicController@update')->name('forum.topic.update');
                Route::post('{id}/rebase', 'ForumTopicController@rebase')->name('forum.topic.rebase');

                /**set reputation like/dislike*/
                Route::post('{id}/set_rating', 'TopicRatingController@setRating')->name('forum.topic.set_rating');

                Route::group(['prefix' => 'comment'], function () {
                    Route::post('/store', 'TopicCommentController@store')->name('forum.topic.comment.store');
                    Route::get('{id}/delete', 'TopicCommentController@destroy')->name('forum.topic.comment.delete');
                    Route::post('{id}/update', 'TopicCommentController@update')->name('forum.topic.comment.update');
                });
            });
            Route::get('/{id}', 'ForumTopicController@index')->name('forum.topic.index');
        });
    });

    Route::group(['prefix' => 'replay'], function () {
        Route::get('/', 'ReplayUsersController@index')->name('replay');

        Route::get('/users', 'ReplayUsersController@index')->name('replay.users');
        Route::get('/gosus', 'ReplayGosuController@index')->name('replay.gosus');

        Route::get('/users/paginate', 'ReplayUsersController@paginate')->name('replay.users.paginate');
        Route::get('/gosus/paginate', 'ReplayGosuController@paginate')->name('replay.gosus.paginate');

        Route::get('/users/{type}', 'ReplayUsersController@index')->name('replay.users_type');
        Route::get('/gosus/{type}', 'ReplayGosuController@index')->name('replay.gosus_type');

        Route::get('/gosus/{type}/paginate',
            'ReplayGosuController@getReplayByType')->name('replay.gosus_type.paginate');
        Route::get('/users/{type}/paginate',
            'ReplayGosuController@getReplayByType')->name('replay.users_type.paginate');

        /**reputation replay list*/
        Route::get('/{id}/get_rating', 'ReplayRatingController@getRating')->name('replay.get_rating');
        Route::get('/{id}/pagination', 'ReplayRatingController@paginate')->name('replay.paginate');

        Route::get('/{id}/get_evaluation', 'ReplayRatingController@getEvaluation')->name('replay.get_evaluation');
        Route::get('/{id}/download', 'ReplayController@download')->name('replay.download');

        /**search form in sidebar widget*/
        Route::get('search', 'ReplayController@index')->name('replay.search');
        Route::get('search/paginate', 'ReplayController@paginate')->name('replay.search.paginate');

        Route::group(['middleware' => 'auth'], function () {
            Route::get('/create', 'ReplayController@create')->name('replay.create');
            Route::post('/store', 'ReplayController@store')->name('replay.store');
            Route::get('/{id}/edit', 'ReplayController@edit')->name('replay.edit');
            Route::post('/{id}/update', 'ReplayController@update')->name('replay.update');
            Route::get('{id}/delete', 'ReplayController@destroy')->name('replay.delete');

            Route::get('/my', 'ReplayUsersController@getUserReplay')->name('replay.my_user');
            Route::get('/my/paginate', 'ReplayUsersController@getUserReplayPaginate')->name('replay.my_users.paginate');

            Route::get('/my_gosu', 'ReplayGosuController@getUserReplay')->name('replay.my_gosu');
            Route::get('/my_gosu/paginate', 'ReplayGosuController@getUserReplayPaginate')->name('replay.my_gosus.paginate');

            Route::post('{id}/set_rating', 'ReplayRatingController@setRating')->name('replay.set_rating');
            Route::post('{id}/set_evaluation', 'ReplayRatingController@setEvaluation')->name('replay.set_evaluation');
            Route::group(['prefix' => 'comment'], function () {
                Route::post('/store', 'ReplayCommentController@store')->name('replay.comment.store');
                Route::get('{id}/delete', 'ReplayCommentController@destroy')->name('replay.comment.delete');
                Route::post('{id}/update', 'ReplayCommentController@update')->name('replay.comment.update');
            });
        });
        Route::get('/{id}', 'ReplayController@show')->name('replay.get');
    });

    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/', 'UserGalleryController@index')->name('gallery.list');
        Route::get('/my', 'UserGalleryController@indexUser')->name('gallery.list_my');

        Route::get('/user/{id}', 'UserGalleryController@indexUser')->name('gallery.list_user');
        Route::get('/user/{id}/paginate', 'UserGalleryController@indexUser')->name('gallery.list_user.paginate');

        /**reputation gallery list*/
        Route::get('/{id}/get_rating', 'UserGalleryRatingController@getRating')->name('gallery.get_rating');
        Route::get('/{id}/pagination', 'UserGalleryRatingController@paginate')->name('gallery.paginate');

        Route::group(['middleware' => 'auth', 'prefix' => 'photo'], function () {
            Route::get('/create', 'UserGalleryController@create')->name('gallery.create');
            Route::get('/{id}/edit', 'UserGalleryController@edit')->name('gallery.edit');
            Route::post('/store', 'UserGalleryController@store')->name('gallery.store');
            Route::post('/{id}/update', 'UserGalleryController@update')->name('gallery.update');
            Route::post('{id}/set_rating', 'UserGalleryRatingController@setRating')->name('gallery.set_rating');

            Route::group(['prefix' => 'comment'], function () {
                Route::post('/store', 'UserGalleryCommentController@store')->name('gallery.comment.store');
                Route::get('{id}/delete', 'UserGalleryCommentController@destroy')->name('gallery.comment.delete');
                Route::post('{id}/update', 'UserGalleryCommentController@update')->name('gallery.comment.update');
            });
        });
        Route::get('/photo/{id}', 'UserGalleryController@show')->name('gallery.view');
    });

    Route::group(['middleware' => ['auth', 'admin_panel'], 'prefix' => 'admin_panel', 'namespace' => 'Admin'],
        function () {
            Route::get('/', 'BaseController@index')->name('admin.home');
            Route::post('send_quick_email', 'BaseController@sendQuickEmail')->name('admin.send_quick_email');
            Route::group(['prefix' => 'comment'], function () {
                Route::get('/{object_name}/{id}', 'CommentController@getComments')->name('admin.comments');
                Route::get('/{id}', 'CommentController@removeComment')->name('admin.comments.remove');
            });

            Route::group(['prefix' => 'user'], function () {
                Route::get('{id}/email', 'UserEmailController@index')->name('admin.user.email');
                Route::get('{id}/replay', 'ReplayController@getReplayByUser')->name('admin.user.replay');
                Route::get('{id}/topic', 'ForumTopicController@getUsersTopics')->name('admin.user.topic');
                Route::get('{id}/profile', 'UserController@getUserProfile')->name('admin.user.profile');
                Route::get('{id}/ban', 'UserController@banUser')->name('admin.user.ban');
                Route::get('{id}/remove', 'UserController@removeUser')->name('admin.user.remove');
                Route::get('{id}/not_ban', 'UserController@notBanUser')->name('admin.user.not_ban');
                Route::get('{id}/profile/edit', 'UserController@getEditUserProfile')->name('admin.user.profile.edit');
                Route::post('{id}/profile/save', 'UserController@saveUserProfile')->name('admin.user.profile.save');
                Route::get('/', 'UserController@index')->name('admin.users');
                Route::get('/pagination', 'UserController@pagination')->name('admin.users.pagination');

                Route::get('{id}/message', 'UserMessageController@getUser')->name('admin.user.messages');
                Route::get('message', 'UserMessageController@getUser')->name('admin.user.messages_all');
                Route::get('/message/{dialog_id}/load', 'UserMessageController@load')->name('admin.user.message_load');
                Route::post('/message/{dialog_id}/send', 'UserMessageController@send')->name('admin.user.message.send');

                Route::group(['prefix' => 'role'], function () {
                    Route::get('/', 'UserRoleController@index')->name('admin.users.role');
                    Route::get('/{id}/edit', 'UserRoleController@edit')->name('admin.user.role.edit');
                    Route::get('/{id}/remove', 'UserRoleController@remove')->name('admin.user.role.remove');
                    Route::get('/add', 'UserRoleController@add')->name('admin.user.role.add');
                    Route::post('/create', 'UserRoleController@create')->name('admin.user.role.create');
                    Route::post('/{id}/save', 'UserRoleController@save')->name('admin.user.role.save');
                });

                Route::group(['prefix' => 'gallery'], function () {
                    Route::get('/', 'UserGalleryController@index')->name('admin.users.gallery');
                    Route::get('/pagination',
                        'UserGalleryController@pagination')->name('admin.users.gallery.pagination');
                    Route::get('/{id}/view', 'UserGalleryController@view')->name('admin.users.gallery.view');
                    Route::get('/{id}/for_adults',
                        'UserGalleryController@forAdults')->name('admin.users.gallery.for_adults');
                    Route::get('/{id}/not_for_adults',
                        'UserGalleryController@notForAdults')->name('admin.users.gallery.not_for_adults');
                    Route::get('/{id}/remove', 'UserGalleryController@remove')->name('admin.users.gallery.remove');
                    Route::post('/{id}/send_comment',
                        'UserGalleryController@sendComment')->name('admin.user.gallery.comment_send');
                    Route::get('/{id}/edit', 'UserGalleryController@edit')->name('admin.user.gallery.edit');
                    Route::post('/{id}/update', 'UserGalleryController@update')->name('admin.user.gallery.update');
                    Route::get('/add', 'UserGalleryController@add')->name('admin.user.gallery.add');
                    Route::post('/create', 'UserGalleryController@create')->name('admin.user.gallery.create');
                });

                Route::group(['prefix' => '{id}/reputation'], function () {
                    Route::get('/', 'ReputationController@index')->name('admin.user.reputation');
                    Route::get('/pagination',
                        'ReputationController@pagination')->name('admin.user.reputation.pagination');
                    Route::get('{reputation_id}/remove',
                        'ReputationController@remove')->name('admin.user.reputation.remove');
                });

                Route::group(['prefix' => '{id}/comments'], function () {
                    Route::get('/', 'UserCommentController@index')->name('admin.user.comments');
                    Route::get('/pagination',
                        'UserCommentController@pagination')->name('admin.user.comments.pagination');
                    Route::get('{comments_id}/remove',
                        'UserCommentController@remove')->name('admin.user.comments.remove');
                });

                Route::group(['prefix' => '{id}/answers'], function () {
                    Route::get('/', 'UserQuestionsController@index')->name('admin.user.answers');
                    Route::get('/pagination',
                        'UserQuestionsController@pagination')->name('admin.user.answers.pagination');
                    Route::get('{comments_id}/remove',
                        'UserQuestionsController@remove')->name('admin.user.answers.remove');
                });
            });

            Route::group(['prefix' => 'forum'], function () {
                Route::get('/', 'ForumController@index')->name('admin.forum_sections');
                Route::get('/pagination', 'ForumController@pagination')->name('admin.forum_sections.pagination');
                Route::get('/add', 'ForumController@getSectionAdd')->name('admin.forum.section.add');
                Route::post('/add', 'ForumController@createSection')->name('admin.forum.section.create');
                Route::get('{id}/active', 'ForumController@active')->name('admin.forum.section.active');
                Route::get('{id}/unactive', 'ForumController@unactive')->name('admin.forum.section.not_active');
                Route::get('{id}/general', 'ForumController@general')->name('admin.forum.section.general');
                Route::get('{id}/not_general', 'ForumController@notGeneral')->name('admin.forum.section.not_general');
                Route::get('{id}/user_can', 'ForumController@userCan')->name('admin.forum.section.user_can');
                Route::get('{id}/user_not_can', 'ForumController@userNotCan')->name('admin.forum.section.user_not_can');
                Route::get('{id}/remove', 'ForumController@remove')->name('admin.forum.section.remove');
                Route::get('{id}/edit', 'ForumController@getSectionEdit')->name('admin.forum.section.edit');
                Route::post('{id}/edit', 'ForumController@saveSection')->name('admin.forum.section.edit.save');


                Route::group(['prefix' => 'topic'], function () {
                    Route::get('/', 'ForumTopicController@topics')->name('admin.forum_topic');
                    Route::get('/pagination', 'ForumTopicController@pagination')->name('admin.forum_topic.pagination');
                    Route::get('/add', 'ForumTopicController@getTopicAdd')->name('admin.forum.topic.add');
                    Route::post('/add', 'ForumTopicController@createTopic')->name('admin.forum.topic.create');
                    Route::get('/{id}/news', 'ForumTopicController@news')->name('admin.forum.topic.news');
                    Route::get('/{id}/not_news', 'ForumTopicController@notNews')->name('admin.forum.topic.not_news');
                    Route::get('/{id}/approve', 'ForumTopicController@approve')->name('admin.forum.topic.approve');
                    Route::get('/{id}/unapprove',
                        'ForumTopicController@unapprove')->name('admin.forum.topic.unapprove');
                    Route::get('/{id}/remove', 'ForumTopicController@remove')->name('admin.forum.topic.remove');
                    Route::get('/{id}/edit', 'ForumTopicController@getTopicEdit')->name('admin.forum.topic.edit');
                    Route::post('/{id}/edit', 'ForumTopicController@saveTopic')->name('admin.forum.topic.edit.save');
                    Route::get('/{id}', 'ForumTopicController@getTopic')->name('admin.forum.topic.get');
                    Route::post('/{id}/send_comment',
                        'TopicCommentController@sendComment')->name('admin.forum.topic.comment_send');
                });
            });
            Route::group(['prefix' => 'replay'], function () {
                Route::get('/', 'ReplayController@index')->name('admin.replay');
                Route::get('/pagination', 'ReplayController@pagination')->name('admin.replay.pagination');
                Route::get('/{id}/user_rating', 'ReplayController@getUserRating')->name('admin.replay.user_rating');
                Route::get('/{id}/approve', 'ReplayController@approve')->name('admin.replay.approve');
                Route::get('/{id}/not_approve', 'ReplayController@notApprove')->name('admin.replay.not_approve');
                Route::get('/{id}/remove', 'ReplayController@remove')->name('admin.replay.remove');
                Route::get('/{id}/view', 'ReplayController@getReplay')->name('admin.replay.view');
                Route::get('/{id}/edit', 'ReplayController@edit')->name('admin.replay.edit');
                Route::get('/add', 'ReplayController@add')->name('admin.replay.add');
                Route::post('/create', 'ReplayController@create')->name('admin.replay.create');
                Route::post('/{id}/edit', 'ReplayController@save')->name('admin.replay.save');
                Route::post('/{id}/send_comment', 'ReplayController@sendComment')->name('admin.replay.comment_send');

                Route::group(['prefix' => 'map'], function () {
                    Route::get('/', 'ReplayMapController@index')->name('admin.replay.map');
                    Route::get('/pagination', 'ReplayMapController@pagination')->name('admin.replay.map.pagination');
                    Route::get('/{id}/remove', 'ReplayMapController@remove')->name('admin.replay.map.remove');
                    Route::get('/{id}/edit', 'ReplayMapController@edit')->name('admin.replay.map.edit');
                    Route::post('/{id}/update', 'ReplayMapController@update')->name('admin.replay.map.update');
                    Route::get('/add', 'ReplayMapController@add')->name('admin.replay.map.add');
                    Route::post('/create', 'ReplayMapController@create')->name('admin.replay.map.create');
                });

                Route::group(['prefix' => 'type'], function () {
                    Route::get('/', 'ReplayTypeController@index')->name('admin.replay.type');
                    Route::get('/{id}/edit', 'ReplayTypeController@edit')->name('admin.replay.type.edit');
                    Route::get('/{id}/remove', 'ReplayTypeController@remove')->name('admin.replay.type.remove');
                    Route::get('/add', 'ReplayTypeController@add')->name('admin.replay.type.add');
                    Route::post('/create', 'ReplayTypeController@create')->name('admin.replay.type.create');
                    Route::post('/{id}/save', 'ReplayTypeController@save')->name('admin.replay.type.save');
                });
            });

            Route::group(['prefix' => 'country'], function () {
                Route::get('/', 'CountryController@index')->name('admin.country');
                Route::get('/pagination', 'CountryController@pagination')->name('admin.country.pagination');
                Route::get('/{id}/edit', 'CountryController@edit')->name('admin.country.edit');
                Route::get('/{id}/remove', 'CountryController@remove')->name('admin.country.remove');
                Route::get('/add', 'CountryController@add')->name('admin.country.add');
                Route::post('/create', 'CountryController@create')->name('admin.country.create');
                Route::post('/{id}/save', 'CountryController@save')->name('admin.country.save');
            });

            Route::group(['prefix' => 'question'], function () {
                Route::get('/', 'InterviewQuestionController@index')->name('admin.question');
                Route::get('/pagination', 'InterviewQuestionController@pagination')->name('admin.question.pagination');
                Route::get('/{id}/active', 'InterviewQuestionController@active')->name('admin.question.active');
                Route::get('/{id}/not_active',
                    'InterviewQuestionController@notActive')->name('admin.question.not_active');
                Route::get('/{id}/for_login', 'InterviewQuestionController@forLogin')->name('admin.question.for_login');
                Route::get('/{id}/not_for_login',
                    'InterviewQuestionController@notForLogin')->name('admin.question.not_for_login');
                Route::get('/{id}/favorite', 'InterviewQuestionController@favorite')->name('admin.question.favorite');
                Route::get('/{id}/not_favorite',
                    'InterviewQuestionController@notFavorite')->name('admin.question.not_favorite');
                Route::get('/{id}/view', 'InterviewQuestionController@view')->name('admin.question.view');
                Route::get('/{id}/edit', 'InterviewQuestionController@edit')->name('admin.question.edit');
                Route::get('/{id}/remove', 'InterviewQuestionController@remove')->name('admin.question.remove');
                Route::get('/add', 'InterviewQuestionController@add')->name('admin.question.add');
                Route::post('/create', 'InterviewQuestionController@create')->name('admin.question.create');
                Route::post('/{id}/save', 'InterviewQuestionController@save')->name('admin.question.save');
            });

            Route::group(['prefix' => 'file'], function () {
                Route::get('/', 'FileManagementController@index')->name('admin.file');
                Route::get('/pagination', 'FileManagementController@pagination')->name('admin.file.pagination');
                Route::get('/{id}/edit', 'FileManagementController@edit')->name('admin.file.edit');
                Route::get('/{id}/remove', 'FileManagementController@remove')->name('admin.file.remove');
                Route::post('/{id}/update', 'FileManagementController@update')->name('admin.file.update');
                Route::get('/{id}/download', 'FileManagementController@download')->name('admin.file.download');
            });

            Route::group(['prefix' => 'footer'], function () {
                Route::get('/', 'FooterManagementController@index')->name('admin.footer');
                Route::get('/create', 'FooterManagementController@create')->name('admin.footer.create');
                Route::get('/{id}/edit', 'FooterManagementController@edit')->name('admin.footer.edit');
                Route::post('/{id}/update', 'FooterManagementController@update')->name('admin.footer.update');
                Route::get('/{id}/view', 'FooterManagementController@show')->name('admin.footer.view');
                Route::post('/store', 'FooterManagementController@store')->name('admin.footer.store');
                Route::get('/{id}/delete', 'FooterManagementController@destroy')->name('admin.footer.delete');
                Route::get('/{id}/approved', 'FooterManagementController@approved')->name('admin.footer.approved');
                Route::get('/{id}/not_approved','FooterManagementController@notApproved')->name('admin.footer.not_approved');
            });

            Route::group(['prefix' => 'banner'], function () {
                Route::get('/', 'BannerManagementController@index')->name('admin.banner');
                Route::get('/create', 'BannerManagementController@create')->name('admin.banner.create');
                Route::get('/{id}/edit', 'BannerManagementController@edit')->name('admin.banner.edit');
                Route::post('/{id}/update', 'BannerManagementController@update')->name('admin.banner.update');
                Route::get('/{id}/view', 'BannerManagementController@show')->name('admin.banner.view');
                Route::post('/store', 'BannerManagementController@store')->name('admin.banner.store');
                Route::get('/{id}/delete', 'BannerManagementController@destroy')->name('admin.banner.delete');
                Route::get('/{id}/is_active', 'BannerManagementController@isActive')->name('admin.banner.is_active');
                Route::get('/{id}/not_active','BannerManagementController@notActive')->name('admin.banner.not_active');
            });
        });
});

//Redirect old URI
Route::group(['prefix' => 'redirect'], function () {
    Route::get('news', 'RedirectOldURL@news');
    Route::get('user', 'RedirectOldURL@user');
    Route::get('columns', 'RedirectOldURL@columns');
    Route::get('forum', 'RedirectOldURL@forum');
    Route::get('info', 'RedirectOldURL@info');
    Route::get('replays', 'RedirectOldURL@replays');
    Route::get('freereplays', 'RedirectOldURL@freeReplays');
    Route::get('files', 'RedirectOldURL@files');
    Route::get('sc2', 'RedirectOldURL@home');
    Route::get('rating', 'RedirectOldURL@home');
    Route::get('donate', 'RedirectOldURL@home');
    Route::get('userbars', 'RedirectOldURL@home');
    Route::get('home', 'RedirectOldURL@home');
    Route::get('registration', 'RedirectOldURL@registration'); //TODO:: redirect for gallery
});