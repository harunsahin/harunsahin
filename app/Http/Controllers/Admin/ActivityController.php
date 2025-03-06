<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ActivityServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityController extends Controller
{
    /**
     * @var ActivityServiceInterface
     */
    protected $activityService;

    /**
     * ActivityController constructor.
     *
     * @param ActivityServiceInterface $activityService
     */
    public function __construct(ActivityServiceInterface $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Aktivite listesini göster
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activities = $this->activityService->getAll();
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Aktivite detayını göster
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        $activity = $this->activityService->findById($id);
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Kullanıcı aktivitelerini göster
     *
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function userActivities(int $userId)
    {
        $activities = $this->activityService->getByUser($userId);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Modül aktivitelerini göster
     *
     * @param string $module
     * @return \Illuminate\View\View
     */
    public function moduleActivities(string $module)
    {
        $activities = $this->activityService->getByModule($module);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Tarih aralığı aktivitelerini göster
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function dateRangeActivities(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $activities = $this->activityService->getByDateRange($startDate, $endDate);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Tür aktivitelerini göster
     *
     * @param string $type
     * @return \Illuminate\View\View
     */
    public function typeActivities(string $type)
    {
        $activities = $this->activityService->getByType($type);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Aktivite sil
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->activityService->delete($id);
            return response()->json(['success' => true, 'message' => 'Aktivite başarıyla silindi.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Aktivite silinirken bir hata oluştu.'], 500);
        }
    }

    /**
     * Toplu aktivite sil
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->input('ids');
            $this->activityService->bulkDelete($ids);
            return response()->json(['success' => true, 'message' => 'Aktiviteler başarıyla silindi.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Aktiviteler silinirken bir hata oluştu.'], 500);
        }
    }
} 