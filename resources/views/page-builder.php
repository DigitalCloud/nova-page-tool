<link rel="stylesheet" href="/css/grapesjs/toastr.min.css">
<link rel="stylesheet" href="/css/grapesjs/grapes.min.css">
<link rel="stylesheet" href="/css/grapesjs/grapesjs-preset-webpage.min.css">
<link rel="stylesheet" href="/css/grapesjs/tooltip.css">
<link rel="stylesheet" href="/css/grapesjs/grapesjs-plugin-filestack.css">
<link rel="stylesheet" href="/css/grapesjs/demos.css">
<link rel="stylesheet" href="/css/grapesjs/grapes-code-editor.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/js/grapesjs/grapes.js"></script>
<script src="/js/grapesjs/dce-page-builder.js"></script>
<script src="/js/grapesjs/grapes-block-basic.min.js"></script>
<script src="/js/grapesjs/grapesjs-blocks-flexbox.min.js"></script>
<script src="/js/grapesjs/grapesjs-code-editor.min.js"></script>
<script src="/js/grapesjs/grapesjs-plugin-forms.min.js"></script>
<script src="/js/grapesjs/grapesjs-plugin-export.min.js"></script>
<script src="/js/grapesjs/grapesjs-lory-slider.min.js"></script>
<script src="/js/grapesjs/grapesjs-tabs.min.js"></script>
<script src="/js/grapesjs/grapesjs-custom-code.min.js"></script>
<script src="/js/grapesjs/grapesjs-touch.min.js"></script>
<script src="/js/grapesjs/grapesjs-parser-postcss.min.js"></script>
<script src="/js/grapesjs/grapesjs-navbar.min.js"></script>

<div id="gjs"></div>

<!-- pages panel -->
<div id="pages-panel">
    <!-- page form -->
    <div id="page-edit-form">
        <div style="display: block;">
            <div class="gjs-traits-label">Page settings</div>
            <input id="page-id" name="page-id" type="hidden" value="<?= $page->id ?>"/>
            <div class="gjs-trt-traits gjs-one-bg gjs-two-color">
                <div class="gjs-trt-trait">
                    <div class="gjs-label" title="Title">title</div>
                    <div class="gjs-field gjs-field-text">
                        <div class="gjs-input-holder">
                            <input type="text" name="title" placeholder="eg. Text here">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span title="Save" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-save" onClick="savePage()">&nbsp; Save</span>
        <span title="Back" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-mail-reply" onClick="back()">&nbsp; Back</span>
    </div>
</div>

<script>
    var editor = grapesjs.init({
        container: '#gjs',
        storageManager: { autoload: 0 },
        commands: {
            defaults: [
                window['grapesjs-code-editor'].codeCommand,
            ],
        },
        panels: window['grapesjs-code-editor'].panels,
        plugins: [
            'gjs-blocks-basic',
            'gjs-blocks-flexbox',
            'grapesjs-plugin-forms',
            'grapesjs-tabs',
            'grapesjs-plugin-export',
            'grapesjs-custom-code',
            'dce-page-builder',
            'gjs-navbar'
        ],
        styleManager : {
            sectors: [{
                name: 'General',
                open: false,
                buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom']
            },{
                name: 'Dimension',
                open: false,
                buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
            },{
                name: 'Typography',
                open: false,
                buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-shadow'],
            },{
                name: 'Decorations',
                open: false,
                buildProps: ['border-radius-c', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
            },{
                name: 'Extra',
                open: false,
                buildProps: ['transition', 'perspective', 'transform'],
            }
            ],
        },

    });

    var pageEditFormEl = $('#page-edit-form');
    var pageId = $('#page-id').val();
    var currentPage;
    function initPageSettingsModule() {
        PageRepository.init();
    }

    function back() {
        window.window.location.href = document.referrer;
    }

    function savePage() {
        const title = pageEditFormEl.find("[name='title']").val();
        const slug = makeSlug(title);
        const html = editor.getHtml();
        const css = editor.getCss();
        const content = '<style>' + css + '</style><div>' + html + '</div>';

        Object.assign(currentPage, {
            title: title,
            slug: slug,
            html: html,
            css: css,
            content: content
        });
        console.log(currentPage);
        PageRepository.save(currentPage);
    }

    function showPageEditForm(page) {
        pageEditFormEl.show();

        var titleEl = pageEditFormEl.find("[name = 'title']").first();
        titleEl.val(page.title.en);
        //console.log(titleEl, page);

    }

    function renderPage(page) {
        currentPage = page;
        editor.setComponents(page.content);
        //editor.setStyle(page.css);
        showPageEditForm(currentPage);
    }

    // singleton page repository
    var PageRepository = new function () {
        this.url = "/api/page";
        this.init = function () {
            $.getJSON(this.url + '/' + pageId, function (res) {
                renderPage(res);
            })
        }

        this.save = function (page) {
            if (page.id) {
                this.update(page);
            } else {
                this.create(page);
            }
        }

        this.create = function (page) {
            console.log("creating page", page);
            console.log("creating new page");
            $.post(this.url + '/create', JSON.stringify(page), function (res, status) {
                console.log("created page", res);
                PageRepository.pages.push(res);
                currentPage = res;
                populatePageList();
            });
        }

        this.update = function (page) {
            console.log('updating page', page);
            $.post(this.url + '/update', JSON.stringify(page), function (res, status) {
                console.log('updated page', res);
                currentPage = res;
            });
        }

        this.delete = function (page) {
            console.log("deleting page", page);
            const index = this.getIndexByPageId(page.id);
            if (index !== -1) {
                $.post(this.url + '/delete', JSON.stringify(page), function (res, status) {
                    console.log("deleted page", res);
                    PageRepository.pages.splice(index, 1);
                    currentPage = null;
                    populatePageList();
                });

            }
        }

        this.getIndexByPageId = function (id) {
            return findIndexByAttr(this.pages, 'id', id);
        }

    }

    /*
    * return index of a object in an array
    * if no matched obj then return -1
    * [ obj1 , obj2, ... ]
    **/
    function findIndexByAttr(array, attr, value) {
        for (var i = 0; i < array.length; i += 1) {
            if (array[i][attr] === value) {
                return i;
            }
        }
        return -1;
    }

    // make 'hello world' to 'hello-world'
    function makeSlug(s) {
        return s.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }

    initPageSettingsModule();
</script>
