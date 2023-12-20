<?php

namespace App\Nova\Actions;

use App\Models\Body;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportBodies extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $bodies = Http::get('https://api.le-systeme-solaire.net/rest/bodies/')->json()['bodies'];
        collect($bodies)
            ->map(function($body) {
                   return [
                        'name' => $body['name'],
                        'english_name' => $body['englishName'],
                        'is_planet' => $body['isPlanet'],
//                        'moons' => $body['moons'],
                        'semimajor_axis' => $body['semimajorAxis'],
                        'perihelion' => $body['perihelion'],
                        'aphelion' => $body['aphelion'],
                        'eccentricity' => $body['eccentricity'],
                        'inclination' => $body['inclination'],
//                        'masses' => ['massValue' => $body['massValue]$body['mass']['massValue'],
//                        'vol' => $body['vol']['volValue'],
                        'density' => $body['density'],
                        'gravity' => $body['gravity'],
                        'escape' => $body['escape'],
                        'mean_radius' => $body['meanRadius'],
                        'equa_radius' => $body['equaRadius'],
                        'polar_radius' => $body['polarRadius'],
                        'flattening' => $body['flattening'],
                        'dimension' => $body['dimension'],
                        'sideral_orbit' => $body['sideralOrbit'],
                        'sideral_rotation' => $body['sideralRotation'],
//                        'around_planet' => $body['aroundPlanet'],
                        'discovered_by' => $body['discoveredBy'],
                        'discovery_date' => $body['discoveryDate'],
                        'alternative_name' => $body['alternativeName'],
                        'axial_tilt' => $body['axialTilt'],
                        'rel' => $body['rel'],
                        'avg_temp' => $body['avgTemp'],
                        'body_type' => $body['bodyType'],
                       ];
                })
            ->chunk(100)
            ->each(function ($records){
                Body::query()->upsert($records->toArray(), ['name'], ['english_name', 'is_planet', 'moons', 'semimajor_axis', 'perihelion', 'aphelion', 'eccentricity', 'inclination', 'masses', 'vol', 'density', 'gravity', 'escape', 'mean_radius', 'equa_radius', 'polar_radius', 'flattening', 'dimension', 'sideral_orbit', 'sideral_rotation', 'around_planet', 'discovered_by', 'discovery_date', 'alternative_name', 'axial_tilt', 'rel', 'avg_temp', 'body_type']);
            });

        return ActionResponse::message('Bodies imported successfully.');

    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
