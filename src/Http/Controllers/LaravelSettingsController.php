<?php

namespace AnisAronno\LaravelSettings\Http\Controllers;

use AnisAronno\LaravelSettings\Helpers\CacheHelper;
use AnisAronno\LaravelSettings\Http\Requests\StoreLaravelSettingsRequest;
use AnisAronno\LaravelSettings\Http\Requests\UpdateLaravelSettingsRequest;
use AnisAronno\LaravelSettings\Http\Resources\SettingsResources;
use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Exception;
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
                return SettingsProperty::query()->with('user')
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
                    ->paginate(10)->withQueryString();
            }
        );

        return response()->json(SettingsResources::collection($images));
    }

    /**
     * Single Setting Fetch
     * @param $key
     * @return JsonResponse
     */
    public function show($key): JsonResponse
    {
        $settingsProperty = SettingsProperty::findOrFail($key);
        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
            'data' => new SettingsResources($settingsProperty)
        ]);
    }

    /**
     *  Setting Store
     * @param  StoreLaravelSettingsRequest  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreLaravelSettingsRequest $request): JsonResponse
    {
        $settingsProperty = setSettings($request->settings_key, $request->settings_value);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
            'data' => $settingsProperty
        ]);
    }

    /**
     * Summary of update
     * @param  UpdateLaravelSettingsRequest  $request
     * @param $key
     * @return JsonResponse
     */
    public function update(UpdateLaravelSettingsRequest $request, $key): JsonResponse
    {
        try {
            $settingsProperty = updateSettings($key, $request->settings_value);
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
     * @param  string  $key
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $key): JsonResponse
    {
        if(deleteSettings($key)) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully Deleted',
            ]);
        } else {
            return response()->json(['
                    success' => false,
                'message' => 'Delete Failed'
            ]);
        }
    }
}
