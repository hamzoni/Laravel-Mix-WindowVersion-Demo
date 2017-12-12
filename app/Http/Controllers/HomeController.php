<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class HomeController extends Controller
{
    public function index() {
        return view('home');
    }

    public function uploadFiles(Request $request) {
        $file = $request->file('files');
        Storage::disk('local')->put('uploads/'.$file->getClientOriginalName(), file_get_contents($file));
        return $file;
    }

    public function saveFiles() {
        $files = request()->xmls;
        foreach ($files as $file) {
            Storage::disk('local')->put('data/file2.txt', $file);
        }
        return $files;
    }

    public function listFiles() {
        $files = $this->getFiles(Storage::files('uploads/'));
        return ['files' => $files];
    }

    public function listLines() {
        $dir = 'uploads/';
        $fn = request()->fn;
        $lines = $linesv = $ndates = null;
        if (Storage::disk('local')->exists($dir.$fn)) {
            $lines = explode("\n",Storage::get($dir.$fn));
            $linesv = $this->dateNormalize($lines);
            $ndates = $this->dateExtraction($lines);
        }
        return ['lines' => $lines, 'linesv' => $linesv, 'ndates' => $ndates];
    }

    public function dateExtraction($lines) {
        $ndates = [];
        for ($i = 0; $i < count($lines); $i++) {
            if(!preg_match("/\w+/i", $lines[$i])) continue;

            $ls = explode("-", $lines[$i]);
            $ls2 = explode("_", $ls[0]);
            $date = substr($ls2[0],0,4)."/".substr($ls2[0],-4,2)."/".substr($ls2[0],-2,4);
            $time = substr($ls2[1],0,2).":".substr($ls2[1],-5,2).":".substr($ls2[1],-3,2);
            $ndates[$i] = $date." ".$time;
        }
        return $ndates;
    }

    public function dateNormalize($lines) {
        $nlines = [];
        for ($i = 0; $i < count($lines); $i++) {
            if(!preg_match("/\w+/i", $lines[$i])) continue;

            $ls = explode("-", $lines[$i]);
            $ls2 = explode("_", $ls[0]);
            $date = substr($ls2[0],0,4)."/".substr($ls2[0],-4,2)."/".substr($ls2[0],-2,4);
            $time = substr($ls2[1],0,2).":".substr($ls2[1],-5,2).":".substr($ls2[1],-3,2);
            $nlines[$i] = $date." ".$time." - ".$ls[1];
        }
        return $nlines;
    }

    public function getFiles($files) {
        $nfiles = [];
        for ($i = 0; $i < count($files); $i++) {
            $ext = pathinfo($files[$i], PATHINFO_EXTENSION);
            if (strcmp($ext, 'txt') != 0) continue;
            $nfiles[$i] = basename($files[$i]);
        }
        return $nfiles;
    }
}
