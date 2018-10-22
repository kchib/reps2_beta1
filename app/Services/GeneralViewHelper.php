<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.18
 * Time: 12:50
 */

namespace App\Services;


use App\Country;
use App\ForumSection;
use App\InterviewQuestion;
use App\Replay;
use App\User;
use App\UserGallery;
use App\UserMessage;
use App\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GeneralViewHelper
{
    protected $last_forum;
    protected $last_gosu_replay;
    protected $last_user_replay;
    protected $countries;
    protected $user_roles;
    protected $bd_users;

    /**
     * Get random user gallery images
     *
     * @return array
     */
    public function getRandomImg()
    {
        $data_img = UserGallery::with('file')->orderBy('created_at', 'desc')->limit(5000)->get()->toArray();
        $random_img_ids = $data_img?array_rand($data_img,(count($data_img)>4?4:count($data_img))):[];
        $random_img = [];
        foreach ($random_img_ids as $item){
            $random_img[] = $data_img[$item];
        }

        return $random_img;
    }

    /**
     * Get pandom question for user
     *
     * @return mixed
     */
    public function getRandomQuestion()
    {
        return  InterviewQuestion::getRandomQuestion();
    }

    /**
     * @return mixed
     */
    public function getLastForum()
    {
        $this->last_forum =  $this->last_forum??ForumSection::general_active()->with(['topics' =>function($query){
                $query->withCount('comments', 'positive', 'negative')->with('user')->orderBy('created_at', 'desc')->limit(5);
            }])->get();

        return $this->last_forum;
    }

    /**
     * @return mixed
     */
    public function getLastGosuReplay()
    {
        $this->last_gosu_replay = $this->last_gosu_replay??Replay::gosuReplay()->withCount('comments', 'positive', 'negative')->with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        return $this->last_gosu_replay;
    }

    /**
     * @return mixed
     */
    public function getLastUserReplay()
    {
        $this->last_user_replay = $this->last_user_replay??Replay::userReplay()->withCount('comments', 'positive', 'negative')->with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        return $this->last_user_replay;
    }

    /**
     * @return int
     */
    public function getNewUserMessage()
    {
        $new_user_message = 0;

        if (Auth::user()){
            $new_user_message = UserMessage::where('user_recipient_id', Auth::id())->where('is_read',0)->count();
        }

        return $new_user_message;
    }

    /**
     * @return mixed
     */
    public function getNewUsers()
    {
        return  User::where('is_ban',0)->orderBy('created_at', 'desc')->limit(10)->get();
    }

    /**
     * @return Country[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCountries()
    {
        $this->countries = $this->countries??Country::all();
        return $this->countries;
    }

    /**
     * @return UserRole[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsersRole()
    {
        $this->user_roles = $this->user_roles??UserRole::all();
        return $this->user_roles;
    }

    /**
     * @return mixed
     */
    public function getBirthdayUsers()
    {
        $this->bd_users = $this->bd_users??User::where('birthday', Carbon::now()->format('Y-m-d'))->get();
        return $this->bd_users;
    }

    public function getTopReplayAll()
    {

    }

    public function getTopReplayWeek()
    {

    }

    public function getTopReplayMonth()
    {

    }
}