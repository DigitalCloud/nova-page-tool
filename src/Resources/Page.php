<?php

namespace DigitalCloud\PageTool\Resources;

use App\Nova\Resource;
use DigitalCloud\PageBuilderField\PageBuilderField;
use Infinety\Filemanager\FilemanagerField;
use Digitalcloud\MultilingualNova\Multilingual;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'DigitalCloud\PageTool\Models\Page';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Hide resource from Nova's standard menu.
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'content'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title'),
            PageBuilderField::make('Content'),
            Select::make('Status')->options(['pending' => 'Pending Review', 'draft' => 'Draft', 'published' => 'Published']),
            Select::make('Visibility')->options(['public' => 'Public', 'private' => 'Private', 'protected' => 'Protected']),
            DateTime::make('Publishing on', 'scheduled_for'),
            FilemanagerField::make('Featured Image')->displayAsImage(),
            Text::make('Preview')->withMeta(['value' => '<a href="/page/1" target="_blank">Preview</a>'])->asHtml()->onlyOnIndex(),
            Multilingual::make('lang'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
