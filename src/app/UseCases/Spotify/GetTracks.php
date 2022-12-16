<?php

namespace App\UseCases\Spotify;

use App\Helper\SpotifyApi;
use App\Models\Tracks;

class GetTracks
{
    public function __construct(SpotifyApi $spotify_api)
    {
        $this->spotify_api = $spotify_api;
    }

    public function invoke()
    {
        $search_items = $this->spotify_api->execRandomQuerySearch('track', ['market' => 'JP']);
        $this->saveTrack($search_items->tracks);
        $this->execNextUrl($search_items->tracks->next);
    }

    private function saveTrack($tracks)
    {
        foreach ($tracks->items as $item) {
            if ($this->validateTrack($item)) {
                $artists = '';
                foreach ($item->artists as $artist) {
                    $artists .= $artist->name . ',';
                }
                Tracks::upsert(
                    [
                        'external_url' => $item->external_urls->spotify,
                        'artists' => rtrim($artists, ','),
                        'popularity' => $item->popularity,
                        'duration_ms' => $item->duration_ms,
                        'isrc' => $item->external_ids->isrc,
                    ],
                    ['external_url'],
                );
            }
        }
    }

    private function execNextUrl($next_url)
    {
        while (!is_null($next_url)) {
            $search_items = json_decode(json_encode($this->spotify_api->execURL($next_url)));
            if (isset($search_items->error)) {
                return;
            }

            $this->saveTrack($search_items->tracks);
            $next_url = $search_items->tracks->next;
        }
    }

    private function validateTrack($item)
    {
        if (
            $this->isIsrcJp($item->external_ids->isrc)
            && $this->validateTime($item->duration_ms)
        ) {
            return true;
        }
        return false;
    }

    private function validateTime($msec)
    {
        // 1minute = 60000ms
        $oneminuteToMsec = 60000;

        // 任意の分数 +- ALLOWANCE_MSEC を許容する
        // 下記の値だと+-5秒を許容するため、n分55秒~n分05秒の曲を許容する
        $allowanceMsec = 5000;

        for ($minute = 1; $minute <= 8; $minute++) {
            $allowanceMsecMin = $minute * $oneminuteToMsec - $allowanceMsec;
            $allowanceMsecMax = $minute * $oneminuteToMsec + $allowanceMsec;
            if (
                $msec >= $allowanceMsecMin
                && $msec <= $allowanceMsecMax
            ) {
                return true;
            }
        }
        return false;
    }

    private function isIsrcJp($isrc)
    {
        if (substr($isrc, 0, 2) === 'JP') {
            return true;
        }
        return false;
    }
}
