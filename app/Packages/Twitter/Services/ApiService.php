<?php

namespace App\Packages\Twitter\Services;

use App\Models\Template;
use App\Packages\Twitter\Jobs\TweetMessage;
use App\Packages\Twitter\Models\TwitterAccount;
use App\Packages\Twitter\Models\TwitterQueue;
use Carbon\Carbon;
use ChillDev\Spintax\Parser;
use TwitterOAuth;
use URL;

class ApiService
{
    public function authenticate()
    {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $twitter->getRequestToken(URL::to(route('twitter.token')));

        if ($twitter->http_code == '200') {

            session()->put('twitter.token', $request_token['oauth_token']);
            session()->put('twitter.token_secret', $request_token['oauth_token_secret']);

            $twitter_url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $request_token['oauth_token'];

            return $twitter_url;
        }
        return URL::to(route('twitter-account.index'));
    }

    public function token()
    {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, session('twitter.token'), session('twitter.token_secret'));
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        if ($connection->http_code == '200') {
            return $access_token;
        }
        throw new \Exception('Couldn\'t connect to Twitter.');
    }

    public function tweet($tweetData)
    {
        $client_id = $tweetData['client_id'];
        $template_id = $tweetData['template_id'];
        $delay = $tweetData['delay'];

        $queue = TwitterQueue::create([
            'client_id' => $client_id,
            'template_id' => $template_id,
            'tweet_count' => 0,
            'jobs' => 0
        ]);
        $jobs = 0;

        $template = Template::find($template_id);
        $spintax = Parser::parse($template->message);

        foreach ($tweetData['queue'] as $twitterAccount) {
            $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $twitterAccount['oauth_token'], $twitterAccount['oauth_secret']);

            $followers = $twitter->get('followers/list', array("count" => 200));
            $followers = json_decode(json_encode($followers), true);

            foreach ($followers['users'] as $follower) {
                dispatch((new TweetMessage($queue,
                    "@{$follower['screen_name']} " . $spintax->generate(),
                    $twitterAccount
                ))->delay(Carbon::now()->addSeconds($delay)));
            }

            $jobs++;
        }

        $queue->update(['jobs' => $jobs]);

        return $queue;
    }

    public function postTweet(TwitterAccount $account, $message)
    {
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $account->oauth_token, $account->oauth_secret);

        //Insert user into the database
        $tweet = $connection->post('statuses/update', ['status' => $message]);

        return $tweet;
    }
}