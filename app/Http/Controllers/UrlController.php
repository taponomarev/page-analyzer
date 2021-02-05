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
        $sites = DB::table('urls')->paginate(15);
        return view('urls.index', ['sites' => $sites]);
    }

    /**
     * @param string $url_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(string $url_id)
    {
        $site = DB::table('urls')->where('id', $url_id)->first();
        return view('urls.show', ['site' => $site]);
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

        $todayCarbonDate = Carbon::today();
        DB::table('urls')->insert([
            'name' => $normalizeUrl,
            'created_at' => $todayCarbonDate->toDateString(),
            'updated_at' => $todayCarbonDate->toDateString()
        ]);

        flash("Site '{$normalizeUrl}' added successfully!")->success();
        return redirect(route('urls'));
    }
}
