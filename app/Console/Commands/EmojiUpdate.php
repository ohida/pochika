<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmojiUpdate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'emoji:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update emoji data';

    protected $json_path;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->json_path = storage_path('emoji/emojis.json');

            $this->downloadJson();
            //$this->downloadEmoji();

            $this->info('emoji data updated');
            $this->line($this->json_path);
        } catch (\Exception $e) {
            $this->error('error: '.$e->getMessage());
        }
    }

    protected function downloadJson()
    {
        $url = 'https://api.github.com/emojis';

        return $this->download($url, $this->json_path);
    }

    protected function downloadEmoji()
    {
        $dir = public_path('emoji');
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        $json = file_get_contents($this->json_path);
        $urls = json_decode($json, true);

        foreach ($urls as $name => $url) {
            $this->line('download: '.$name);
            $path = $dir.'/'.$name.'.png';
            $this->download($url, $path);
        }
    }

    protected function download($url, $path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Pochika');
        $buff = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 != $http_code) {
            throw new \RuntimeException('cannot download: '.$url);
        }

        if (!file_put_contents($path, $buff)) {
            throw new \RuntimeException('cannot save: '.$path);
        }

        return true;
    }
}
