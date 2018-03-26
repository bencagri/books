<?php

namespace App\Http\Controllers;

use App\Entities\BookAudio;
use App\Repositories\BookAudioRepository;
use App\Service\BookAudioService;
use App\Support\AppController;
use Illuminate\Support\Facades\Storage;
use SteemAPI\SteemAPI;
use Illuminate\Support\Facades\Cache;

class ListenController extends AppController
{
    /**
     * @var BookAudioService
     */
    private $bookAudioService;
    /**
     * @var BookAudioRepository
     */
    private $audioRepository;

    /**
     * ListenController constructor
     *
     * @param BookAudioService $bookAudioService
     * @param BookAudioRepository $audioRepository
     */
    public function __construct(
        BookAudioService $bookAudioService,
        BookAudioRepository $audioRepository
    )
    {
        $this->bookAudioService = $bookAudioService;
        $this->audioRepository = $audioRepository;
    }

    /**
     * Listen to an book audio
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        /** @var BookAudio $bookAudio */
        $bookAudio = $this->audioRepository->find($id);

        if(!$bookAudio) {
            return abort(404);
        }

        if($bookAudio->getStatus() == BookAudioRepository::STATUS_PENDING) {
            return view('audio-listen', ['id' => $id, 'audio' => $bookAudio]);
        }

        if (Cache::has("book_audio_{$id}") && 0) {
            $result = Cache::get("book_audio_{$id}");
        } else {

            $result['fileSource'] = Storage::disk('s3')->temporaryUrl(remote_path($bookAudio->getFileSource()), now()->addMinutes(30));
            $result['replies'] = NULL;
            $result['body'] = NULL;

            if( NULL !== $bookAudio->getSteemSlug()) {
                $steem     = new SteemAPI();
                $replies   = $steem->getPost()->getContentAllReplies($bookAudio->getUser()->getAccount(), $bookAudio->getSteemSlug());

                $result    = [
                    'body'    => $steem->getPost()->getContent($bookAudio->getUser()->getAccount(), $bookAudio->getSteemSlug()),
                    'replies' => $replies,
                ];

                Cache::put("book_audio_{$id}", $result, config('cache.expire'));
            }

        }

        return view('audio-listen', ['id' => $id, 'audio' => $bookAudio, 'data' => $result]);
    }

    /**
     * Embed player
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function embed($id)
    {
        $bookAudio = $this->bookAudioService->find($id);
        return view('audio-embed', ['data' => $bookAudio]);
    }
}
