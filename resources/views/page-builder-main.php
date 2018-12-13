<link rel="stylesheet" href="css/grapesjs/toastr.min.css">
<link rel="stylesheet" href="css/grapesjs/grapes.min.css">
<link rel="stylesheet" href="css/grapesjs/grapesjs-preset-webpage.min.css">
<link rel="stylesheet" href="css/grapesjs/tooltip.css">
<link rel="stylesheet" href="css/grapesjs/grapesjs-plugin-filestack.css">
<link rel="stylesheet" href="css/grapesjs/demos.css">
<link rel="stylesheet" href="css/grapesjs/grapes-code-editor.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/grapesjs/grapes.js"></script>
<script src="js/grapesjs/dce-page-builder.js"></script>
<script src="js/grapesjs/grapes-block-basic.min.js"></script>
<script src="js/grapesjs/grapesjs-blocks-flexbox.min.js"></script>
<script src="js/grapesjs/grapesjs-code-editor.min.js"></script>
<script src="js/grapesjs/grapesjs-plugin-forms.min.js"></script>
<script src="js/grapesjs/grapesjs-plugin-export.min.js"></script>
<script src="js/grapesjs/grapesjs-lory-slider.min.js"></script>
<script src="js/grapesjs/grapesjs-tabs.min.js"></script>
<script src="js/grapesjs/grapesjs-custom-code.min.js"></script>
<script src="js/grapesjs/grapesjs-touch.min.js"></script>
<script src="js/grapesjs/grapesjs-parser-postcss.min.js"></script>
<script src="js/grapesjs/grapesjs-navbar.min.js"></script>

<div id="gjs"></div>

<!-- pages panel -->
<div id="pages-panel">

    <span title="New Page" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-plus" onClick="createNewPage()">&nbsp; New Page</span>
    <!-- page list -->
    <div>
        <ul id="page-list" style="list-style: none;" hidden>
        </ul>
    </div>
    <!-- page form -->
    <div id="page-edit-form">
        <div>
            <div style="display: block;">
                <div class="gjs-traits-label">Page settings</div>
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
        </div>
        <span title="Save" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-save" onClick="savePage()">&nbsp; Save</span>
        <span title="Cancel" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-close" onClick="hidePageEditForm()">&nbsp; Cancel</span>
<!--        <span title="Delete" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-ban" onClick="deletePage()">&nbsp; Delete</span>-->
    </div>
</div>

<script>
    var editor = grapesjs.init({
        container: '#gjs',
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
    editor.Commands.add('saveTemplate', {
        run: function(editor, senderBtn) {
            senderBtn.set('active', false);
            savePage()
        },
        stop: function(editor, sender) {}
    });
    editor.Panels.addButton('options', {
        id: 'save-template-btn',
        command: 'saveTemplate',
        className: 'fa fa-save'
    });

    var pageEditFormEl = $('#page-edit-form');
    var pageListEl = $('#page-list');
    var currentPage;

    function initPageSettingsModule() {
        PageRepository.init();
    }

    function populatePageList() {
        var pages = PageRepository.pages;

        pageListEl.children().remove();
        for (i = 0; i < pages.length; i++) {
            var page = pages[i];
            var btn = $('<span title="Edit" style="float: left" data-tooltip-pos="bottom" class="gjs-pn-btn fa fa-paper-plane">&nbsp;' + page.title.en + '</span>');
            var li = $('<li></li>').append(btn);

            btn.click(page, function ($event) {
                editPage($event.data)
            });
            pageListEl.append(li);
        }
        //console.log(currentPage);
        if (!pages.length && !currentPage) {
            currentPage = getDefaultPage();
            showPageEditForm(currentPage);
        }

        currentPage = currentPage || pages[0];
        //currentPage = getDefaultPage();
        renderPage(currentPage);

    }


    function editPage(page) {
        renderPage(page);
        showPageEditForm(page);
        console.log("currently editing ", currentPage);
    }

    function createNewPage() {
        currentPage = getDefaultPage();

        editor.setComponents('');
        editPage(currentPage);
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

        PageRepository.save(currentPage);
        //console.log(currentPage);
    }

    function deletePage() {
        //console.log(currentPage.id);
        PageRepository.delete(currentPage);
    }

    function getDefaultPage() {
        return {
            title: 'New Page',
            html: '<h1>This is New Page</h1>'
        }
    }


    function showPageEditForm(page) {
        pageEditFormEl.show();
        pageListEl.hide();

        var titleEl = pageEditFormEl.find("[name = 'title']").first();
        titleEl.val(page.title.en);
        //console.log(titleEl, page);

    }

    function hidePageEditForm() {
        pageEditFormEl.hide();
        pageListEl.show();
    }


    function renderPage(page) {
        currentPage = page;
        editor.setComponents(page.html);
        editor.setStyle(page.css);
        showPageEditForm(currentPage);
    }






    // singleton page repository
    var PageRepository = new function () {
        this.url = "/api/page"
        this.pages = [
            // { id: 1, slug: 'about', title: 'About', html: '<h1>About</h1>', css: 'h1{color:red}' },
            // { id: 2, slug: 'contact', title: 'Contact', html: '<h1>Contact</h1>', css: 'h1{color:green}' }
        ];

        this.init = function () {
            $.getJSON(this.url + '/list', function (res) {
                PageRepository.pages = res;
                populatePageList();
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
            const index = PageRepository.getIndexByPageId(page.id);

            if (index !== -1) {
                $.post(this.url + '/update', JSON.stringify(page), function (res, status) {
                    console.log('updated page', page);
                    PageRepository.pages[index] = res;
                    currentPage = res;
                    populatePageList();
                });

            }
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
