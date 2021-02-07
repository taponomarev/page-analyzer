<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlCheckController extends Controller
{
    /**
     * @param string $url_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(string $url_id)
    {
        $site = DB::table('urls')->find($url_id);
        $response = Http::get($site->name);
        $nowDate = now();

        DB::table('url_checks')->insert([
            'url_id' => $url_id,
            'status_code' => $response->status(),
            'created_at' => $nowDate,
            'updated_at' => $nowDate
        ]);

        flash("The page has been verified successfully!")->success();
        return redirect(route('urls.show', $url_id));
    }
}
