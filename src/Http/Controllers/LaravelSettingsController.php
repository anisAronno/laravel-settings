<?php

namespace AnisAronno\LaravelSettings\Http\Controllers;

use AnisAronno\LaravelSettings\Helpers\CacheHelper;
use AnisAronno\LaravelSettings\Http\Requests\StoreLaravelSettingsRequest;
use AnisAronno\LaravelSettings\Http\Requests\UpdateLaravelSettingsRequest;
use AnisAronno\LaravelSettings\Http\Resources\SettingsResources;
use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LaravelSettingsController extends Controller
{
    /**
     * Get ALl Image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orderBy = $request->query('orderBy', 'created_at');
        $order = $request->query('order', 'desc');
        $search = $request->query('search', '');
        $startDate = $request->query('startDate', '');
        $endDate = $request->query('endDate', '');
        $page = $request->query('page', 1);

        $imageCacheKey = CacheHelper::getLaravelSettingsCacheKey();

        $key =  $imageCacheKey.md5(serialize([$orderBy, $order,  $page, $search, $startDate, $endDate,  ]));

        $images = CacheHelper::init($imageCacheKey)->remember(
            $key,
            now()->addDay(),
            function () use ($request) {
                return SettingsProperty::query()
                    ->when($request->has('search'), function ($query) use ($request) {
                        $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
                    })
                    ->when($request->has('startDate') && $request->has('endDate'), function ($query) use ($request) {
                        $query->whereBetween('created_at', [
                            new \DateTime($request->input('startDate')),
                            new \DateTime($request->input('endDate'))
                        ]);
                    })
                    ->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'))
                    ->paginate(20)->withQueryString();
            }
        );

        return response()->json(SettingsResources::collection($images));
    }

    /**
    * Single Setting Fetch
    * @param SettingsProperty $settings
    * @return \Illuminate\Http\RedirectResponse
    */
    public function show(SettingsProperty $settingsProperty)
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
            'data' => new SettingsResources($settingsProperty)
        ]);
    }

    /**
    *  Setting Store
    * @param StoreLaravelSettingsRequest $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(StoreLaravelSettingsRequest $request)
    {
        $settings = SettingsProperty::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
            'data' => $settings]);
    }

    /**
    * Summary of update
    * @param UpdateLaravelSettingsRequest $request
    * @param SettingsProperty $settings
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(UpdateLaravelSettingsRequest $request, SettingsProperty $settingsProperty)
    {
        try {
            $settingsProperty = $settingsProperty::updateSettings($settingsProperty->settings_key, $request->settings_value);
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated',
                'data' => $settingsProperty
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }

    }

    /**
     * Summary of destroy
     * @param SettingsProperty $settings
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SettingsProperty $settingsProperty)
    {
        try {
            $settingsProperty::updateOption($settingsProperty->settings_key, null);

            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated',
                'data' => $settingsProperty
            ]);
        } catch (\Throwable $th) {
            return response()->json(['
                success' => false,
                'message' => 'Update Failed'
            ]);
        }
    }

    /**
    * Summary of bulkUpdate
    * @param UpdateLaravelSettingsRequest $request
    * @param SettingsProperty $settingsProperty
    * @return \Illuminate\Http\RedirectResponse
    */
    public function bulkUpdate(UpdateLaravelSettingsRequest $request, SettingsProperty $settingsProperty)
    {
        $data = $request->only('settings_key', 'settings_value') ;

        try {
            foreach ($data as $key => $value) {
                $settingsProperty::updateOption($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated',
                'data' => $settingsProperty
            ]);
        } catch (\Throwable $th) {
            return response()->json(['
                success' => false,
                'message' => 'Update Failed'
            ]);
        }
    }
}
