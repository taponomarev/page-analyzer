<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $subQuery = DB::table('url_checks')
            ->selectRaw('url_id, MAX(id) AS max_id')
            ->groupBy('url_id');
        $urls = DB::table('urls', 'u')
            ->leftJoinSub($subQuery, 'ch1', 'ch1.url_id', '=', 'u.id')
            ->leftJoin('url_checks', 'url_checks.id', '=', 'ch1.max_id')
            ->select(
                'u.id',
                'u.name',
                'url_checks.status_code AS last_check_status_code',
                'url_checks.created_at AS last_checked_at'
            )
            ->paginate(15);
        return view('urls.index', ['urls' => $urls]);
    }

    /**
     * @param string $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(string $id)
    {
        $url = DB::table('urls')->find($id);
        $urlChecks = DB::table('url_checks')
            ->where('url_id', $id)
            ->get()
            ->reverse();
        return view('urls.show', ['url' => $url, 'urlChecks' => $urlChecks]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        /* @phpstan-ignore-next-line */
        $request->validate([
            'url.name' => 'required|active_url',
        ]);

        /** @var string[] */
        $urlInfo = parse_url($request->get('url')['name']);
        $normalizeUrl = "{$urlInfo['scheme']}://{$urlInfo['host']}";

        $urlData = DB::table('urls')->where('name', $normalizeUrl)->first();

        if (!is_null($urlData)) {
            flash('Site already exists!')->error();
            return redirect('/');
        }

        DB::table('urls')->insert([
            'name' => $normalizeUrl,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        flash("Url '{$normalizeUrl}' added successfully!")->success();
        return redirect(route('urls'));
    }

    /**
     * @param string $urlId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function storeCheck(string $urlId)
    {
        $site = DB::table('urls')->find($urlId);
        $response = Http::get($site->name);

        $parsedData = $this->getParsedData($response->body());

        if (is_null($parsedData)) {
            flash("The site not available")->success();
            return redirect(route('urls.show', $urlId));
        }

        [$h1, $description, $keywords] = $parsedData;

        DB::table('url_checks')->insert([
            'url_id' => $urlId,
            'status_code' => $response->status(),
            'h1' => $h1,
            'description' => $description,
            'keywords' => $keywords,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        flash("The Site has been verified successfully!")->success();
        return redirect(route('urls.show', $urlId));
    }

    /**
     * @param string $html
     * @return array|null
     */
    public function getParsedData(string $html): ?array
    {
        if ($html === '') {
            return null;
        }

        $document = new Document();
        try {
            $document->loadHtml($html);

            $h1 = optional($document->first('h1'))->text();
            $description = optional($document->first('meta[name="description]'))->getAttribute('content');
            $keywords = optional($document->first('meta[name="keywords]'))->getAttribute('content');

            return [
                $h1,
                $description,
                $keywords
            ];
        } catch (\Exception $exception) {
            return null;
        }
    }
}
