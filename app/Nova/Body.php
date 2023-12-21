<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Body extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Body>
     */
    public static $model = \App\Models\Body::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make("Name")->sortable(),
            Text::make("English Name")->sortable(),
            Boolean::make("Is Planet")->sortable(),
            Text::make("Moons")->sortable()->hideFromIndex(),
            Text::make("Semimajor Axis")->sortable()->hideFromIndex(),
            Text::make("Perihelion")->sortable()->hideFromIndex(),
            Text::make("Aphelion")->sortable()->hideFromIndex(),
            Text::make("Eccentricity")->sortable()->hideFromIndex(),
            Text::make("Inclination")->sortable()->hideFromIndex(),
            Text::make("Masses")->sortable()->hideFromIndex(),
            Text::make("Vol")->sortable()->hideFromIndex(),
            Text::make("Density")->sortable()->hideFromIndex(),
            Text::make("Gravity")->sortable()->hideFromIndex(),
            Text::make("Escape")->sortable()->hideFromIndex(),
            Text::make("Mean radius")->sortable()->hideFromIndex(),
            Text::make("Equa radius")->sortable()->hideFromIndex(),
            Text::make("Polar radius")->sortable()->hideFromIndex(),
            Text::make("Escape")->sortable()->hideFromIndex(),
            Text::make("Sideral rrbit")->sortable()->hideFromIndex(),
            Text::make("Sideral rotation")->sortable()->hideFromIndex(),
            Text::make("Around planet")->sortable()->hideFromIndex(),
            Text::make("Discovered by")->sortable(),
            Text::make("Discovery date")->sortable(),
            Text::make("Alternative name")->sortable()->hideFromIndex(),
            Text::make("Axial tilt")->sortable()->hideFromIndex(),
            Text::make("Avg temp")->sortable(),
            Text::make("Main anomaly")->sortable()->hideFromIndex(),
            Text::make("Arg periapsis")->sortable()->hideFromIndex(),
            Text::make("Long asc node")->sortable()->hideFromIndex(),
            Text::make("Body Type")->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            Actions\ImportBodies::make()->standalone()
        ];
    }
}
