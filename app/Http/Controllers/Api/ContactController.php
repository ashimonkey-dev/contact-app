<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * 問い合わせをAPI経由で登録
     */
    public function store(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $application = $request->get('application');
        
        try {
            // バリデーション
            $validator = Validator::make($request->all(), [
                'application_slug' => 'required|string|exists:applications,slug',
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:bug,request,payment,other',
                'title' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'detail' => 'required|string|max:10000',
            ]);

            if ($validator->fails()) {
                $this->logApiRequest($application, $request, 400, null, $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'バリデーションエラー',
                    'errors' => $validator->errors()
                ], 400);
            }

            // アプリケーションのslugが一致するか確認
            if ($application->slug !== $request->application_slug) {
                $this->logApiRequest($application, $request, 400, null, ['application_slug' => ['指定されたアプリケーションが見つかりません']]);
                return response()->json([
                    'success' => false,
                    'message' => '指定されたアプリケーションが見つかりません'
                ], 404);
            }

            // 問い合わせデータ作成
            $contactData = $request->only(['name', 'category', 'title', 'rating', 'detail']);
            $contactData['application_id'] = $application->id;
            
            $contact = Contact::create($contactData);
            
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            // 成功ログ記録
            $this->logApiRequest($application, $request, 201, $responseTime, null, [
                'id' => $contact->id,
                'application_slug' => $contact->application->slug,
                'name' => $contact->name,
                'category' => $contact->category,
                'title' => $contact->title,
                'rating' => $contact->rating,
                'created_at' => $contact->created_at->toISOString()
            ]);

            return response()->json([
                'success' => true,
                'message' => '問い合わせを正常に登録しました',
                'data' => [
                    'id' => $contact->id,
                    'application_slug' => $contact->application->slug,
                    'name' => $contact->name,
                    'category' => $contact->category,
                    'title' => $contact->title,
                    'rating' => $contact->rating,
                    'detail' => $contact->detail,
                    'created_at' => $contact->created_at->toISOString()
                ]
            ], 201);

        } catch (\Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000);
            $this->logApiRequest($application, $request, 500, $responseTime, $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'サーバーエラーが発生しました'
            ], 500);
        }
    }

    /**
     * API統計情報を取得（認証が必要）
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user || !$user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'アクセス権限がありません'
            ], 403);
        }

        $applicationId = $request->get('application_id');
        $period = $request->get('period', '7'); // デフォルト7日間

        $query = ApiLog::whereHas('application', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if ($applicationId) {
            $query->where('application_id', $applicationId);
        }

        // 期間フィルタ
        $query->where('created_at', '>=', now()->subDays($period));

        $stats = $query->selectRaw('
            COUNT(*) as total_requests,
            SUM(CASE WHEN status_code >= 200 AND status_code < 300 THEN 1 ELSE 0 END) as success_requests,
            SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as error_requests,
            AVG(response_time_ms) as avg_response_time,
            COUNT(DISTINCT ip_address) as unique_ips
        ')->first();

        $dailyStats = $query->selectRaw('
            DATE(created_at) as date,
            COUNT(*) as requests,
            SUM(CASE WHEN status_code >= 200 AND status_code < 300 THEN 1 ELSE 0 END) as success,
            SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as errors
        ')->groupBy('date')->orderBy('date')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $stats,
                'daily' => $dailyStats
            ]
        ]);
    }

    /**
     * APIリクエストログを記録
     */
    private function logApiRequest($application, Request $request, int $statusCode, ?int $responseTime, ?string $errorMessage = null, ?array $responseData = null): void
    {
        try {
            ApiLog::create([
                'application_id' => $application->id,
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'status_code' => $statusCode,
                'response_time_ms' => $responseTime,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_data' => $request->except(['application']),
                'response_data' => $responseData,
                'error_message' => $errorMessage,
            ]);
        } catch (\Exception $e) {
            // ログ記録エラーは無視（無限ループを防ぐため）
        }
    }
}
