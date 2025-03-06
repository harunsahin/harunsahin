<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Interfaces\CommentServiceInterface;
use App\Interfaces\ActivityServiceInterface;
use App\Interfaces\LogServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    protected $commentService;
    protected $activityService;
    protected $logService;

    public function __construct(
        CommentServiceInterface $commentService,
        ActivityServiceInterface $activityService,
        LogServiceInterface $logService
    ) {
        $this->commentService = $commentService;
        $this->activityService = $activityService;
        $this->logService = $logService;
    }

    public function index()
    {
        try {
            // SQL sorgularını loglayalım
            \DB::enableQueryLog();
            
            $comments = $this->commentService->getAll();
            
            // SQL sorgularını loglayalım
            \Log::info('SQL Sorguları:', [
                'queries' => \DB::getQueryLog()
            ]);
            
            // Debug için detaylı log ekleyelim
            \Log::info('Yorumlar listeleniyor', [
                'count' => $comments->count(),
                'total' => $comments->total(),
                'has_more_pages' => $comments->hasMorePages(),
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'data' => $comments->toArray()
            ]);
            
            return view('admin.comments.index', compact('comments'));
        } catch (\Exception $e) {
            $this->logService->create([
                'name' => 'Yorum Listesi Hatası',
                'level' => 'error',
                'message' => 'Yorumlar listelenirken hata oluştu: ' . $e->getMessage(),
                'context' => [
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ],
                'path' => request()->path(),
                'method' => request()->method(),
                'ip' => request()->ip()
            ]);
            \Log::error('Yorumlar listelenirken hata detayı: ' . $e->getTraceAsString());
            return back()->with('error', 'Yorumlar listelenirken bir hata oluştu.');
        }
    }

    public function create()
    {
        return view('admin.comments.create');
    }

    public function store(Request $request)
    {
        try {
            \DB::enableQueryLog();
            
            // Validasyon kurallarını al
            $validator = Validator::make($request->all(), Comment::getValidationRules());
            
            if ($validator->fails()) {
                \Log::error('Validasyon hataları:', $validator->errors()->toArray());
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasyon hatası',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Validasyon başarılı, veriyi hazırla
            $data = $validator->validated();
            
            // Kullanıcı bilgilerini ekle
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            
            // Tarih formatını düzelt
            if (isset($data['comment_date'])) {
                $date = \DateTime::createFromFormat('d.m.Y', $data['comment_date']);
                if ($date) {
                    $data['comment_date'] = $date->format('Y-m-d');
                }
            }
            
            // Varsayılan değerleri ayarla
            $data['status'] = $data['status'] ?? true;
            $data['position'] = $data['position'] ?? 0;
            
            \Log::info('Yorum kaydediliyor:', $data);
            
            // Yorumu oluştur
            $comment = $this->commentService->create($data);
            
            \Log::info('Yorum başarıyla oluşturuldu:', ['id' => $comment->id]);
            
            // Aktivite logu oluştur
            $this->activityService->create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'module' => 'comments',
                'comment_id' => $comment->id,
                'old_values' => [],
                'new_values' => $comment->toArray()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Yorum başarıyla oluşturuldu.',
                    'data' => $comment
                ]);
            }
            
            return redirect()->route('admin.comments.index')
                ->with('success', 'Yorum başarıyla oluşturuldu.');
            
        } catch (\Exception $e) {
            \Log::error('Yorum oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum oluşturulurken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Yorum oluşturulurken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Comment $comment)
    {
        try {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $comment
                ]);
            }
            return view('admin.comments.show', compact('comment'));
        } catch (ModelNotFoundException $e) {
            $this->logService->create([
                'name' => 'Yorum Bulunamadı',
                'level' => 'error',
                'message' => 'Yorum bulunamadı: ' . $e->getMessage(),
                'context' => ['trace' => $e->getTraceAsString()],
                'path' => request()->path(),
                'method' => request()->method(),
                'ip' => request()->ip()
            ]);
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum bulunamadı.'
                ], 404);
            }
            return back()->with('error', 'Yorum bulunamadı.');
        } catch (\Exception $e) {
            $this->logService->create([
                'name' => 'Yorum Detay Görüntüleme Hatası',
                'level' => 'error',
                'message' => 'Yorum detayı görüntülenirken hata: ' . $e->getMessage(),
                'context' => ['trace' => $e->getTraceAsString()],
                'path' => request()->path(),
                'method' => request()->method(),
                'ip' => request()->ip()
            ]);
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum detayı görüntülenirken bir hata oluştu.'
                ], 500);
            }
            return back()->with('error', 'Yorum detayı görüntülenirken bir hata oluştu.');
        }
    }

    public function edit(Comment $comment)
    {
        try {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $comment
                ]);
            }
            return view('admin.comments.edit', compact('comment'));
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Yorum bulunamadı: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum bulunamadı.'
                ], 404);
            }
            return back()->with('error', 'Yorum bulunamadı.');
        } catch (\Exception $e) {
            $this->logService->error('Yorum düzenleme sayfası açılırken hata: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum düzenleme sayfası açılırken bir hata oluştu.'
                ], 500);
            }
            return back()->with('error', 'Yorum düzenleme sayfası açılırken bir hata oluştu.');
        }
    }

    public function update(Request $request, Comment $comment)
    {
        try {
            \DB::enableQueryLog();
            
            // Validasyon
            $validator = Validator::make($request->all(), Comment::getValidationRules());
            
            if ($validator->fails()) {
                \Log::error('Yorum güncelleme validasyon hatası:', [
                    'errors' => $validator->errors()->toArray(),
                    'data' => $request->all()
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasyon hatası',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $oldValues = $comment->toArray();
            $data = $validator->validated();
            $data['updated_by'] = Auth::id();

            \Log::info('Yorum güncelleniyor:', [
                'comment_id' => $comment->id,
                'data' => $data
            ]);

            $updatedComment = $this->commentService->update($comment->id, $data);

            \Log::info('Yorum başarıyla güncellendi:', ['comment' => $updatedComment]);

            $this->activityService->create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model' => 'Comment',
                'model_id' => $comment->id,
                'description' => 'Yorum güncellendi',
                'old_values' => $oldValues,
                'new_values' => $updatedComment->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Yorum başarıyla güncellendi.'
                ]);
            }

            return redirect()->route('admin.comments.index')
                ->with('success', 'Yorum başarıyla güncellendi.');

        } catch (ModelNotFoundException $e) {
            \Log::error('Yorum bulunamadı:', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum bulunamadı.'
                ], 404);
            }
            
            return back()->with('error', 'Yorum bulunamadı.');
        } catch (\Exception $e) {
            \Log::error('Yorum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yorum güncellenirken bir hata oluştu.'
                ], 500);
            }

            return back()->with('error', 'Yorum güncellenirken bir hata oluştu.')
                ->withInput();
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $oldValues = $comment->toArray();
            $this->commentService->delete($comment->id);

            $this->activityService->create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'module' => 'comments',
                'description' => 'Yorum silindi',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'comment_id' => $comment->id,
                'old_values' => $oldValues,
                'new_values' => null
            ]);

            $this->logService->error('Yorum silindi', [
                'comment_id' => $comment->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yorum başarıyla silindi.'
            ]);
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Yorum bulunamadı: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorum bulunamadı.'
            ], 404);
        } catch (\Exception $e) {
            $this->logService->error('Yorum silinirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorum silinirken bir hata oluştu.'
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:comments,id'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $ids = $request->input('ids');
            $this->commentService->bulkDelete($ids);

            $this->logService->info('Toplu yorum silme işlemi gerçekleştirildi', [
                'comment_ids' => $ids,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Seçili yorumlar başarıyla silindi.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $this->logService->error('Toplu yorum silme işlemi başarısız: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorumlar silinirken bir hata oluştu.'
            ], 500);
        }
    }

    public function updateStatus(Request $request, Comment $comment)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $oldValues = $comment->toArray();
            $this->commentService->updateStatus($comment->id, $request->status);

            $this->activityService->create([
                'user_id' => Auth::id(),
                'action' => 'update_status',
                'module' => 'comments',
                'comment_id' => $comment->id,
                'old_values' => $oldValues,
                'new_values' => $comment->fresh()->toArray()
            ]);

            $this->logService->info('Yorum durumu güncellendi', [
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'new_status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yorum durumu başarıyla güncellendi.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri.',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Yorum bulunamadı: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorum bulunamadı.'
            ], 404);
        } catch (\Exception $e) {
            $this->logService->error('Yorum durumu güncellenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorum durumu güncellenirken bir hata oluştu.'
            ], 500);
        }
    }

    public function getByDateRange(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $comments = $this->commentService->getByDateRange(
                $request->start_date,
                $request->end_date
            );

            return response()->json([
                'success' => true,
                'data' => $comments
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz tarih aralığı.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $this->logService->error('Tarih aralığına göre yorumlar listelenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorumlar listelenirken bir hata oluştu.'
            ], 500);
        }
    }

    public function getLatest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $limit = $request->input('limit', 10);
            $comments = $this->commentService->getLatest($limit);

            return response()->json([
                'success' => true,
                'data' => $comments
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz limit değeri.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $this->logService->error('Son yorumlar listelenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorumlar listelenirken bir hata oluştu.'
            ], 500);
        }
    }

    public function getLogs(Comment $comment)
    {
        try {
            $logs = $this->logService->getByCommentId($comment->id);
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            Log::error('Yorum logları alınırken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Yorum logları alınırken bir hata oluştu.'
            ], 500);
        }
    }
} 