<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkDeleteRequest;
use App\Http\Requests\Admin\ReorderRequest;
use App\Http\Requests\Admin\ResourceDefinitionRequest;
use App\Interfaces\ResourceDefinitionServiceInterface;
use App\Traits\HandlesControllerErrors;

class ResourceDefinitionController extends Controller
{
    use HandlesControllerErrors;

    /**
     * @var ResourceDefinitionServiceInterface
     */
    protected $service;

    /**
     * Constructor
     */
    public function __construct(ResourceDefinitionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource definitions.
     */
    public function index()
    {
        return $this->handleControllerAction(
            function () {
                $resourceDefinitions = $this->service->getAll();
                return view('admin.resource-definitions.index', compact('resourceDefinitions'));
            },
            'Kaynak tanımları listesi yüklenirken hata oluştu'
        );
    }

    /**
     * Show the form for creating a new resource definition.
     */
    public function create()
    {
        return $this->handleControllerAction(
            function () {
                return view('admin.resource-definitions.create');
            },
            'Kaynak tanımı oluşturma formu yüklenirken hata oluştu'
        );
    }

    /**
     * Store a newly created resource definition in storage.
     */
    public function store(ResourceDefinitionRequest $request)
    {
        return $this->handleControllerAction(
            function () use ($request) {
                $this->service->create($request->validated());

                return redirect()->route('admin.resource-definitions.index')
                    ->with('success', 'Kaynak tanımı başarıyla oluşturuldu.');
            },
            'Kaynak tanımı oluşturulurken hata oluştu'
        );
    }

    /**
     * Show the form for editing the specified resource definition.
     */
    public function edit($id)
    {
        return $this->handleControllerAction(
            function () use ($id) {
                $resourceDefinition = $this->service->findById($id);
                return $this->successResponse([
                    'kaynak' => $resourceDefinition->kaynak,
                    'is_active' => $resourceDefinition->is_active,
                    'position' => $resourceDefinition->position
                ]);
            },
            'Kaynak tanımı düzenleme formu yüklenirken hata oluştu'
        );
    }

    /**
     * Update the specified resource definition in storage.
     */
    public function update(ResourceDefinitionRequest $request, $id)
    {
        return $this->handleControllerAction(
            function () use ($request, $id) {
                $this->service->update($id, $request->validated());

                return redirect()->route('admin.resource-definitions.index')
                    ->with('success', 'Kaynak tanımı başarıyla güncellendi.');
            },
            'Kaynak tanımı güncellenirken hata oluştu'
        );
    }

    /**
     * Remove the specified resource definition from storage.
     */
    public function destroy($id)
    {
        return $this->handleControllerAction(
            function () use ($id) {
                $this->service->delete($id);

                return redirect()->route('admin.resource-definitions.index')
                    ->with('success', 'Kaynak tanımı başarıyla silindi.');
            },
            'Kaynak tanımı silinirken hata oluştu'
        );
    }

    /**
     * Bulk delete resource definitions.
     */
    public function bulkDelete(BulkDeleteRequest $request)
    {
        return $this->handleControllerAction(
            function () use ($request) {
                $this->service->bulkDelete($request->validated()['ids']);

                return redirect()->route('admin.resource-definitions.index')
                    ->with('success', 'Seçili kaynak tanımları başarıyla silindi.');
            },
            'Toplu kaynak tanımı silme işlemi sırasında hata oluştu'
        );
    }

    /**
     * Reorder resource definitions.
     */
    public function reorder(ReorderRequest $request)
    {
        return $this->handleControllerAction(
            function () use ($request) {
                $this->service->reorder($request->validated()['order']);

                return $this->successResponse([], 'Kaynak tanımları başarıyla sıralandı.');
            },
            'Kaynak tanımı sıralama işlemi sırasında hata oluştu'
        );
    }
} 