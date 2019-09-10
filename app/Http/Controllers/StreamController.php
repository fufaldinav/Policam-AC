<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function index() {
        set_time_limit(0);

        $desccpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $cwd = "C:/ffmpeg";

        $connectionType = 'tcp';
        $cmd = "-rtsp_transport {$connectionType} -re -i";

        $address = "rtsp://192.168.1.30:554/cam/realmonitor?channel=1&subtype=0&unicast=true&proto=Onvif";
        $hlsTime = 5;
        $hlsListSize = 10;
        $hlsSegmentFilename = 'C:\ffmpeg\video\%03d.ts';
        $hlsPlaylist = 'C:\ffmpeg\video\live.m3u8';
        $sTimeout = 5000;

        $cmd .= ' "' . $address . '" -codec copy -f hls -hls_time ' . $hlsTime . ' -hls_list_size ' . $hlsListSize . ' -hls_segment_filename ';
        $cmd .= '"' . $hlsSegmentFilename . '" "' . $hlsPlaylist . '" -stimeout ' . $sTimeout;

        $result = '';
        $stdout = '';
        $stderr = '';
        $closecode = '';

        $proc = proc_open("C:/ffmpeg/ffmpeg.exe {$cmd}", $desccpec, $pipes, $cwd, null, ['bypass_shell' => true]);

        $status = proc_get_status($proc);

        $result .= print_r($status, true);

        if (is_resource($proc)) {
            fwrite($pipes[0], '');
            fclose($pipes[0]);

            $stdout .= stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stderr .= stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $closecode .= proc_close($proc);
        }

        $result = ">>>>> STDOUT <<<<<\n{$stdout}\n>>>>> STDERR <<<<<\n{$stderr}\n>>>>> CLOSE CODE {$closecode} <<<<<";

        $result = print_r($result, true);

        return response($result)
            ->header('Content-Type', 'text/plain; charset=windows-866')
            ->header('charset', 'windows-866');
    }
}
