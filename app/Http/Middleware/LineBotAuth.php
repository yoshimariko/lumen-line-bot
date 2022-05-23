<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Exception\UnknownEventTypeException;
use LINE\LINEBot\Exception\UnknownMessageTypeException;

class LineBotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bot = app(LINEBot::class);
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE, '');

        if (empty($signature)) {
            return response()->json(['message' => 'Bad Request'],400);
        }

        try {
            $events = $bot->parseEventRequest($request->getContent(), $signature);
        } catch (InvalidSignatureException $e) {
            return response()->json(['message' => 'Invalid signature'],400);
        } catch (UnknownEventTypeException $e) {
            return response()->json(['message' => 'Unknown event type has come'],400);
        } catch (UnknownMessageTypeException $e) {
            return response()->json(['message' => 'Unknown message type has come'],400);
        } catch (InvalidEventRequestException $e) {
            return response()->json(['message' => 'Invalid event request'],400);
        }

        $request->merge(['botevents' => $events]);

        return $next($request);
    }
}
