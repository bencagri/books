<?php

namespace App\EventListeners;

use App\Entities\BookAudio;
use App\Entities\SteemLogs;
use App\Entities\User;
use App\Events\AudioApproved;
use App\Repositories\BookAudioRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Auth;
use Steem\Steemit;

class PostToSteemListener
{
    /**
     * @var BookAudio
     */
    private $bookAudio;


    /**
     * Handle Steem Post
     *
     * @param AudioApproved $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function handle(AudioApproved $event)
    {
        $this->bookAudio = $event->bookAudio;

        /** @var EntityManager $em */
        $em = app('em');

        if ($this->bookAudio->getStatus() == BookAudioRepository::STATUS_APPROVED) {
            /** @var User $user */
            $user = $this->bookAudio->getUser();

            $steem = new Steemit();

            $tags = [];

            if(!empty($this->bookAudio->getTags()->getValues())){
                foreach ($this->bookAudio->getTags()->toArray() as $item) {
                    $tags[] = $item->getName();
                }
            }

            $exec =
                [
                    $user->getAccount(),
                    $this->bookAudio->getName(),
                    view('posts.signature',[
                        'id'   => $this->bookAudio->getId(),
                        'post' => $this->bookAudio->getBody()
                    ])->render(),
                    array_merge([config('steem.tag')],$tags),
                    config('services.steem.client_id')
                ];

            $response = $steem->setToken($user->getAccessToken())->exec('post',$exec);

            if(isset($response['error']) || !isset($response['result'])) {
                //log
                $this->log($response,$exec,'post',$em, $this->bookAudio);
            }else{
                //update steem slug and post first comment
                $response = $response['result']['operations'][0][1]; // orul orul orospu çocukluğu bu
                $slug = sprintf("@%s/%s",$response['author'],$response['permlink']);

                $this->bookAudio->setSteemSlug($slug);
                $this->bookAudio->activate();
                $em->flush($this->bookAudio);

                //add first comment
                $user = Auth::user();
                $exec =
                    [
                        $response['author'],
                        $response['permlink'],
                        $user->getAccount(),
                        "approved-{$this->bookAudio->getName()}",
                        view('posts.moderator-approve', ['comment' => $this->bookAudio->getModComment()])->render(),
                        config('services.steem.client_id')
                    ];

                $response = $steem->setToken($user->getAccessToken())->exec('comment',$exec);

                if(isset($response['error']) || !isset($response['result'])){
                    $this->log($response,$exec,'comment',$em, $this->bookAudio);
                }

            }

        }
    }


    /**
     * Refactor for loggin in mysql
     * @param $response
     * @param $request
     * @param $type
     * @param EntityManager $em
     * @param null $post
     * @internal param $log
     */
    public function log($response,$request, $type, EntityManager $em,$post = null)
    {
        $response = $response ? $response : [];

        $log = new SteemLogs();
        $log->setRequest($request);
        $log->setResponse($response);
        $log->setType($type);
        if(NULL != $post){
            $log->setBookAudio($post);
        }
        $em->persist($log);
        $em->flush();
    }

}
