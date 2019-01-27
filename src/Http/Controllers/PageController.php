<?php

namespace DigitalCloud\PageTool\Http\Controllers;

use DigitalCloud\PageTool\Models\Page;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller {

    public function index() {
        $pages = Page::all();
        return view('page-tool::page-list', compact('pages'));
    }
    public function detail($id) {
        $page = Page::find($id);
        if(!Auth::id() && $page->getAttribute('vaisibility') != 'public') {
            return 'This Page is ' . $page->getAttribute('visibility') . '. You must login to preview this page.';
        }
        return view('page-tool::page-detail', compact('page'));

    }
}
