<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Requests\{CommentUpdateRequest, ReplaySearchAdminRequest, ReplayStoreRequest, ReplayUpdateRequest};
use App\{Replay, ReplayMap, ReplayType, ReplayUserRating};
use App\Services\Base\{BaseDataService, AdminViewService};
use App\Services\Comment\CommentService;
use App\Services\Replay\ReplayService;
use App\Http\Controllers\Controller;

class ReplayController extends Controller
{
    /**
     * @param ReplaySearchAdminRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ReplaySearchAdminRequest $request)
    {
        $data = ReplayService::search($request)->count();
        return view('admin.replay.replays')->with(['replay_count' => $data, 'request_data' => $request->validated()]);
    }

    /**
     * @param ReplaySearchAdminRequest $request
     * @return array
     */
    public function pagination(ReplaySearchAdminRequest $request)
    {
        $data = $data = Replay::getReplay($request);
        return BaseDataService::getPaginationData(AdminViewService::getReplay($data), AdminViewService::getPagination($data), AdminViewService::getReplayPopUp($data));
    }

    /**
     * Get replays by user
     *
     * @param ReplaySearchAdminRequest $request
     * @param $user_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getReplayByUser(ReplaySearchAdminRequest $request, $user_id)
    {
        return view('admin.replay.replays')->with(ReplayService::getReplayByUser($request,$user_id));
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserRating($replay_id)
    {
        $data = ReplayUserRating::where('replay_id', $replay_id)->with('user')->orderBy('created_at', 'desc')->limit(100)->get();
        return view('admin.replay.user_rating')->with('data', $data);
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($replay_id)
    {
        Replay::where('id', $replay_id)->update(['approved' => 1]);
        return back();
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notApprove($replay_id)
    {
        Replay::where('id', $replay_id)->update(['approved' => 0]);
        return back();
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($replay_id)
    {
       ReplayService::remove($replay_id);
        return back();
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReplay($replay_id)
    {
        return view('admin.replay.view')->with('replay', $this->getReplayObject($replay_id));
    }

    /**
     * @param CommentUpdateRequest $request
     * @param $replay_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendComment(CommentUpdateRequest $request, $replay_id)
    {
        $data = $request->validated();
        CommentService::create($data, Comment::RELATION_REPLAY, $replay_id);
        return back();
    }

    /**
     * @param $replay_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($replay_id)
    {
        return view('admin.replay.edit')->with(['replay'=> $this->getReplayObject($replay_id), 'types' => ReplayType::all(), 'maps' => ReplayMap::all()]);
    }

    /**
     * @param ReplayUpdateRequest $request
     * @param $replay_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(ReplayUpdateRequest $request, $replay_id)
    {
        $replay = Replay::find($replay_id);

        if($replay){
            ReplayService::updateReplay($request, $replay);
            return back();
        }
        return abort(404);
    }

    /**
     * @param $replay_id
     * @return mixed
     */
    private function getReplayObject($replay_id)
    {
        return Replay::getreplayById($replay_id);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('admin.replay.create')->with(['types' => ReplayType::all(), 'maps' => ReplayMap::all()]);
    }

    /**
     * @param ReplayStoreRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ReplayStoreRequest $request)
    {
        return redirect()->route('admin.replay.view', ['id' => ReplayService::store($request)]);
    }
}
