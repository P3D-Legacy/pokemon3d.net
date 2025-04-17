<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['name'];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255')
                ->displayUsing(function ($name, $resource) {
                    return str_repeat('→&emsp;', $resource->depth).$name;
                })->asHtml()->onlyOnIndex(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255')
                ->onlyOnForms(),
            Select::make('Parent Model', 'parent_id')
                ->options(function () {
                    return Category::where('id', '!=', $this->id)
                        ->get()
                        ->reduce(function ($options, $model) {
                            $options[$model['id']] = $model['name'];

                            return $options;
                        }, []);
                })
                ->nullable()
                ->onlyOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $request->viaRelationship()
            ? $query
            : $query->withDepth()->defaultOrder();
    }
}
