<?php
/**
 * Created by PhpStorm.
 * User: nonoca
 * Date: 2017/03/19
 * Time: 23:42
 */
namespace App\Http\Controllers;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Exception\UnknownEventTypeException;
use LINE\LINEBot\Exception\UnknownMessageTypeException;

class CallbackController extends Controller
{
    public function index(LINEBot $bot, Request $req){

        $events = $req->botevents;
        
        foreach ($events as $event) {
            if (!($event instanceof MessageEvent)) {
                Log::info('Non message event has come');
                continue;
            }
            if (!($event instanceof TextMessage)) {
                Log::info('Non text message has come');
                continue;
            }
            $replyText = $event->getText();
            Log::info('Reply text: ' . $replyText);
            $resp = $bot->replyText($event->getReplyToken(), $replyText);
            Log::info($resp->getHTTPStatus() . ': ' . $resp->getRawBody());
        }
        return response()->json([], 200);
    }
}