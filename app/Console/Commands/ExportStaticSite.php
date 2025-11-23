<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ExportStaticSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-static-site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Exporting site...");

        // ---- LIST THE ROUTES YOU WANT TO EXPORT ----
        $paths = [
            '/',
            '/registration',
            '/login',
            // add more routes here...
        ];


        // ——— FETCH FROM LOCAL LARAVEL ———
        $localBaseUrl = 'http://127.0.0.1:8000';

        // ——— ALL ASSETS WILL BE REWRITTEN TO THIS URL ———
        $githubBaseUrl = 'https://schattaraj.github.io/asrij/public';

        $outputDir = public_path('dist');

        // Create /dist directory
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        foreach ($paths as $path) {

            // Build local fetch URL
            $url = rtrim($localBaseUrl, '/') . $path;

            $this->info("Fetching: $url");

            // Get HTML from localhost
            $html = Http::get($url)->body();

            // ——— REWRITE ALL LOCAL ASSET LINKS TO GITHUB URL ———

            // 1. Replace absolute localhost URLs with GitHub URL
$html = str_replace($localBaseUrl . '/', $githubBaseUrl . '/', $html);

// 2. Replace relative /path links
// $html = str_replace('href="/', 'href="' . $githubBaseUrl . '/', $html);
$html = str_replace('src="/', 'src="' . $githubBaseUrl . '/', $html);

            // // Fix asset() URLs: /css/style.css etc
            // $html = preg_replace(
            //     '#(href|src)="([^"]*)css/#',
            //     '$1="' . $githubBaseUrl . '/css/',
            //     $html
            // );
// Replace full localhost URLs
$html = preg_replace_callback(
    '#\b' . preg_quote($localBaseUrl, '#') . '/([^"\']*)#',
    function ($matches) use ($githubBaseUrl) {
        return $githubBaseUrl . '/' . $matches[1];
    },
    $html
);

// Replace relative paths starting with /
$html = preg_replace_callback(
    '#\b(href|src)=["\'](/[^"\']*)["\']#',
    function ($matches) use ($githubBaseUrl) {
        return $matches[1] . '="' . $githubBaseUrl . $matches[2] . '"';
    },
    $html
);
            // ——— SAVE OUTPUT ———
            $filename = $path == '/' ? 'index.html' : trim($path, '/') . '.html';

            File::put($outputDir . '/' . $filename, $html);

            $this->info("Saved: dist/$filename");
        }

        $this->info("Export complete!");
    }
}
