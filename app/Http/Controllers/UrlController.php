<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrlController extends Controller
{
    public function index()
    {
        $sites = DB::table('urls')->paginate(15);
        return view('urls.index', ['sites' => $sites]);
    }

    public function show(string $url_id)
    {
        $site = DB::table('urls')->where('id', $url_id)->first();
        return view('urls.show', ['site' => $site]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'urls.name' => 'required|active_url',
        ]);

        $partsUrl = parse_url($request->urls['name']);
        $normalizeUrl = "{$partsUrl['scheme']}://{$partsUrl['host']}";

        $urlData = DB::table('urls')->where('name', $normalizeUrl)->first();

        if (!empty($urlData)) {
            flash('Site already exists!')->error();
            return back();
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
