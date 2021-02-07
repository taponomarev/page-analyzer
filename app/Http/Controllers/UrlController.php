<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UrlController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $urls = DB::table('urls')
            ->leftJoin('url_checks', function ($join) {
                $join->on('urls.id', '=', 'url_checks.url_id')
                    ->whereRaw('url_checks.id
                        IN (select MAX(a2.id) from url_checks as a2
                        join urls as u2 on u2.id = a2.url_id group by u2.id)');
            })
            ->select('urls.id', 'urls.name', 'url_checks.status_code', 'url_checks.updated_at')
            ->paginate(15);
        return view('urls.index', ['urls' => $urls]);
    }

    /**
     * @param ?string $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(?string $id)
    {
        $url = DB::table('urls')->where('id', $id)->first();
        $urlChecks = DB::table('url_checks')->where('url_id', $id)->get();
        return view('urls.show', ['url' => $url, 'urlChecks' => $urlChecks]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'urls.name' => 'required|active_url',
        ]);

        /** @var string[] */
        $urlInfo = parse_url($request->get('urls')['name']);
        $normalizeUrl = "{$urlInfo['scheme']}://{$urlInfo['host']}";

        $urlData = DB::table('urls')->where('name', $normalizeUrl)->first();

        if (!empty($urlData)) {
            flash('Site already exists!')->error();
            return \redirect('/');
        }

        $todayCarbonDate = now();
        DB::table('urls')->insert([
            'name' => $normalizeUrl,
            'created_at' => $todayCarbonDate,
            'updated_at' => $todayCarbonDate
        ]);

        flash("Url '{$normalizeUrl}' added successfully!")->success();
        return redirect(route('urls'));
    }
}
