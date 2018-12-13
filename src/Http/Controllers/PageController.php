<?php

namespace DigitalCloud\PageTool\Http\Controllers;

use DigitalCloud\PageTool\Models\Page;

class PageController extends Controller {

    public function index() {
        $data['pages'] = Page::all();
        return view('PageTool::page-list', $data);
    }
    public function detail($id) {
        $data['page'] = Page::find($id);
        return view('PageTool::page-detail', $data);
    }
}
