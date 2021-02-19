<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    public function all(Request $request)
    {
        return $request->user()->urls;
    }

    public function create(Request $request)
    {
        $attrs = $request->validate([
            'url' => 'required|url'
        ],[
            'url.url' => 'The url must be in the correct format.',
        ]);

        $body = [
            'short_url' => Str::random(5),
            'creator_id' => optional($request->user())->id,
        ];

        return Url::query()->forceCreate(array_merge($body, $attrs));

    }

    public function click(Request $request, Url $url)
    {
        DB::table('urls')->where('short_url',$url->short_url)->increment('click_count', 1);
        $url->last_click = Carbon::now();
        $url->save();
        AccessLog::query()->forceCreate([
            'url_id' => $url->id,
            'user_agent' => $request->userAgent(),
        ]);
        return redirect($url->url);
    }


    public function statistics(Url $url)
    {

        $history = AccessLog::query()
            ->where('url_id', $url->id)
            ->selectRaw('count(*) as click_count, concat(year(created_at),".",lpad(month(created_at),2,"0"),".01") as history')
            ->groupBy('history')
            ->orderBy('history')
            ->pluck('click_count', 'history');

        return [
            'total_clicks' => $url->accessLogs()->count(),
            'click_count' => $history
        ];
    }

    public function delete(Url $url)
    {
        $this->authorize('delete', $url);
        $url->delete();
        return 'deleted';
    }


}
