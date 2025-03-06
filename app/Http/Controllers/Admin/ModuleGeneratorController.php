<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Module\ModuleGeneratorService;
use App\Services\Module\ModuleStateService;
use App\Services\Module\Exceptions\ModuleGenerationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleGeneratorController extends Controller
{
    protected $moduleGenerator;
    protected $moduleState;

    public function __construct(
        ModuleGeneratorService $moduleGenerator,
        ModuleStateService $moduleState
    ) {
        $this->middleware('auth');
        $this->middleware('admin');
        
        $this->moduleGenerator = $moduleGenerator;
        $this->moduleState = $moduleState;
    }

    public function index()
    {
        $fieldTypes = config('module.field_types');
        $validationRules = config('module.validation_rules');

        return view('admin.module-generator.index', compact('fieldTypes', 'validationRules'));
    }

    public function generate(Request $request)
    {
        try {
            // Validation
            $validated = $this->validateModuleRequest($request);

            // Modül oluşturma işlemini başlat
            $result = $this->moduleGenerator->generate($validated);

            // Başarılı sonuç döndür
            return response()->json([
                'success' => true,
                'message' => 'Modül başarıyla oluşturuldu.',
                'redirect' => route('admin.modules.index'),
                'module' => [
                    'name' => $result->getName(),
                    'process_id' => $result->getProcessId(),
                    'duration' => $result->getDuration()
                ]
            ]);

        } catch (ModuleGenerationException $e) {
            Log::error('Modül oluşturma hatası:', [
                'message' => $e->getMessage(),
                'state' => $this->moduleState->getCurrentState(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Modül oluşturulurken bir hata oluştu: ' . $e->getMessage(),
                'state' => $this->moduleState->getCurrentState()
            ], 500);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleValidationException($e);
        }
    }

    private function validateModuleRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|min:2',
            'description' => 'nullable|string',
            'is_modal' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|min:2',
            'fields.*.label' => 'required|string|min:2',
            'fields.*.type' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(config('module.field_types')))
            ],
            'fields.*.validation' => 'nullable|array'
        ], $this->getValidationMessages());
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'Modül adı zorunludur.',
            'name.string' => 'Modül adı metin olmalıdır.',
            'name.min' => 'Modül adı en az 2 karakter olmalıdır.',
            'description.string' => 'Açıklama metin olmalıdır.',
            'fields.required' => 'En az bir alan eklemelisiniz.',
            'fields.array' => 'Alanlar listesi geçerli değil.',
            'fields.min' => 'En az bir alan eklemelisiniz.',
            'fields.*.name.required' => 'Alan adı zorunludur.',
            'fields.*.name.string' => 'Alan adı metin olmalıdır.',
            'fields.*.name.min' => 'Alan adı en az 2 karakter olmalıdır.',
            'fields.*.label.required' => 'Alan etiketi zorunludur.',
            'fields.*.label.string' => 'Alan etiketi metin olmalıdır.',
            'fields.*.label.min' => 'Alan etiketi en az 2 karakter olmalıdır.',
            'fields.*.type.required' => 'Alan tipi zorunludur.',
            'fields.*.type.string' => 'Alan tipi metin olmalıdır.',
            'fields.*.type.in' => 'Geçersiz alan tipi. Lütfen listeden bir tip seçin.',
            'fields.*.validation.array' => 'Validasyon kuralları listesi geçerli değil.'
        ];
    }

    private function handleValidationException($e)
    {
        $errors = collect($e->errors())->map(function($messages, $field) {
            $fieldName = str_replace(
                ['fields.', '.name', '.label', '.type', '.validation'],
                ['Alan ', ' - İsim', ' - Etiket', ' - Tip', ' - Validasyon'],
                $field
            );
            $fieldName = ucfirst($fieldName);
            return $fieldName . ': ' . implode(', ', $messages);
        })->values()->all();

        return response()->json([
            'success' => false,
            'message' => 'Lütfen aşağıdaki hataları düzeltin:',
            'errors' => $errors
        ], 422);
    }
} 