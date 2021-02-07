<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrlCheckController extends Controller
{
    /**
     * @param string $url_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(string $url_id)
    {
        DB::table('url_checks')->insert([
            'url_id' => $url_id,
            'status_code' => 200,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        flash("The page has been verified successfully!")->success();
        return redirect(route('urls.show', $url_id));
    }
}
