<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Public\{
    TourController as PublicTourController,
    DestinationController,
    HotelController,
    ContactController,
    NewsletterController
};
use App\Http\Controllers\Api\V1\User\{
    ProfileController,
    BookingController as UserBookingController,
    PaymentController as UserPaymentController
};
use App\Http\Controllers\Api\V1\Admin\{
    DashboardController,
    TourController as AdminTourController,
    BookingController as AdminBookingController,
    ClientController,
    SalesController,
    ReportController,
    SettingsController,
    UserController,
    ConversationController
};

/**
 * ═══════════════════════════════════════════════════════════════════════
 * RUTAS API - PERU MYSTERIOUS TRAVEL ERP
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * UBICACIÓN: routes/api.php (Laravel 12)
 * 
 * ESTRUCTURA:
 * - Health Check
 * - API V1
 *   ├── Públicas (sin auth)
 *   ├── Autenticadas (auth:sanctum)
 *   └── Admin (auth:sanctum + role)
 * 
 * IMPORTANTE: Laravel 12 no usa Kernel.php
 * Los middlewares se registran en bootstrap/app.php o config/
 */

// ═══════════════════════════════════════════════════════════════════════
// HEALTH CHECK - Verificar que la API está funcionando
// ═══════════════════════════════════════════════════════════════════════
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Peru Mysterious API',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
    ]);
});

// ═══════════════════════════════════════════════════════════════════════
// API VERSION 1
// ═══════════════════════════════════════════════════════════════════════
Route::prefix('v1')->group(function () {

    // ═══════════════════════════════════════════════════════════════════
    // AUTENTICACIÓN (Público)
    // ═══════════════════════════════════════════════════════════════════
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        
        // Requieren autenticación
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
        });
    });

    // ═══════════════════════════════════════════════════════════════════
    // TOURS PÚBLICOS
    // ═══════════════════════════════════════════════════════════════════
    Route::prefix('tours')->group(function () {
        Route::get('/', [PublicTourController::class, 'index']);           // Listar con filtros
        Route::get('/featured', [PublicTourController::class, 'featured']); // Destacados
        Route::get('/search', [PublicTourController::class, 'search']);     // Búsqueda
        Route::get('/categories', [PublicTourController::class, 'categories']); // Categorías
        Route::get('/c/{category}', [PublicTourController::class, 'byCategory']); // Por categoría
        Route::get('/{slug}', [PublicTourController::class, 'show']);       // Detalle
    });

    // ═══════════════════════════════════════════════════════════════════
    // DESTINOS PÚBLICOS
    // ═══════════════════════════════════════════════════════════════════
    Route::prefix('destinations')->group(function () {
        Route::get('/', [DestinationController::class, 'index']);
        Route::get('/{slug}', [DestinationController::class, 'show']);
        Route::get('/{slug}/tours', [DestinationController::class, 'tours']);
    });

    // ═══════════════════════════════════════════════════════════════════
    // HOTELES PÚBLICOS
    // ═══════════════════════════════════════════════════════════════════
    Route::prefix('hotels')->group(function () {
        Route::get('/', [HotelController::class, 'index']);
        Route::get('/{slug}', [HotelController::class, 'show']);
        Route::get('/destination/{destination_id}', [HotelController::class, 'byDestination']);
    });

    // ═══════════════════════════════════════════════════════════════════
    // CONTACTO Y NEWSLETTER (Público)
    // ═══════════════════════════════════════════════════════════════════
    Route::post('contact', [ContactController::class, 'submit']);
    Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);

    // ═══════════════════════════════════════════════════════════════════
    // RUTAS PROTEGIDAS (Requieren autenticación)
    // ═══════════════════════════════════════════════════════════════════
    Route::middleware('auth:sanctum')->group(function () {

        // ───────────────────────────────────────────────────────────────
        // PERFIL DE USUARIO
        // ───────────────────────────────────────────────────────────────
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::put('/password', [ProfileController::class, 'updatePassword']);
            Route::post('/avatar', [ProfileController::class, 'uploadAvatar']);
            Route::get('/bookings', [ProfileController::class, 'bookings']);
            Route::get('/preferences', [ProfileController::class, 'preferences']);
            Route::put('/preferences', [ProfileController::class, 'updatePreferences']);
        });

        // ───────────────────────────────────────────────────────────────
        // RESERVAS DE USUARIO
        // ───────────────────────────────────────────────────────────────
        Route::prefix('bookings')->group(function () {
            Route::post('/', [UserBookingController::class, 'store']);
            Route::get('/{id}', [UserBookingController::class, 'show']);
            Route::put('/{id}', [UserBookingController::class, 'update']);
            Route::delete('/{id}/cancel', [UserBookingController::class, 'cancel']);
        });

        Route::get('my-bookings', [UserBookingController::class, 'myBookings']);

        // ───────────────────────────────────────────────────────────────
        // PAGOS
        // ───────────────────────────────────────────────────────────────
        Route::prefix('payments')->group(function () {
            Route::post('/', [UserPaymentController::class, 'process']);
            Route::get('/{id}', [UserPaymentController::class, 'show']);
            Route::get('/booking/{booking_id}', [UserPaymentController::class, 'byBooking']);
        });

        // ═══════════════════════════════════════════════════════════════
        // RUTAS DE ADMINISTRACIÓN (Solo Admin/Super Admin)
        // ═══════════════════════════════════════════════════════════════
        Route::prefix('admin')->middleware('role:admin,super_admin')->group(function () {

            // ───────────────────────────────────────────────────────────
            // DASHBOARD
            // ───────────────────────────────────────────────────────────
            Route::prefix('dashboard')->group(function () {
                Route::get('/stats', [DashboardController::class, 'stats']);
                Route::get('/bookings-chart', [DashboardController::class, 'bookingsChart']);
                Route::get('/sales-chart', [DashboardController::class, 'salesChart']);
                Route::get('/recent-bookings', [DashboardController::class, 'recentBookings']);
                Route::get('/popular-tours', [DashboardController::class, 'popularTours']);
                Route::get('/support-tickets', [DashboardController::class, 'supportTickets']);
            });

            // ───────────────────────────────────────────────────────────
            // TOURS ADMIN (CRUD completo)
            // ───────────────────────────────────────────────────────────
            Route::prefix('tours')->group(function () {
                Route::get('/', [AdminTourController::class, 'index']);
                Route::post('/', [AdminTourController::class, 'store']);
                Route::get('/{id}', [AdminTourController::class, 'show']);
                Route::put('/{id}', [AdminTourController::class, 'update']);
                Route::delete('/{id}', [AdminTourController::class, 'destroy']);
                
                // Acciones especiales
                Route::post('/{id}/images', [AdminTourController::class, 'uploadImages']);
                Route::delete('/{id}/images/{image_id}', [AdminTourController::class, 'deleteImage']);
                Route::patch('/{id}/publish', [AdminTourController::class, 'publish']);
                Route::patch('/{id}/unpublish', [AdminTourController::class, 'unpublish']);
                Route::patch('/{id}/feature', [AdminTourController::class, 'feature']);
                Route::post('/{id}/duplicate', [AdminTourController::class, 'duplicate']);
            });

            // ───────────────────────────────────────────────────────────
            // RESERVAS ADMIN
            // ───────────────────────────────────────────────────────────
            Route::prefix('bookings')->group(function () {
                Route::get('/', [AdminBookingController::class, 'index']);
                Route::post('/', [AdminBookingController::class, 'store']);
                Route::get('/{id}', [AdminBookingController::class, 'show']);
                Route::put('/{id}', [AdminBookingController::class, 'update']);
                Route::delete('/{id}', [AdminBookingController::class, 'destroy']);
                
                // Acciones especiales
                Route::patch('/{id}/status', [AdminBookingController::class, 'updateStatus']);
                Route::patch('/{id}/assign', [AdminBookingController::class, 'assign']);
                Route::get('/calendar', [AdminBookingController::class, 'calendar']);
                Route::get('/export', [AdminBookingController::class, 'export']);
            });

            // ───────────────────────────────────────────────────────────
            // CLIENTES ADMIN
            // ───────────────────────────────────────────────────────────
            Route::prefix('clients')->group(function () {
                Route::get('/', [ClientController::class, 'index']);
                Route::post('/', [ClientController::class, 'store']);
                Route::get('/{id}', [ClientController::class, 'show']);
                Route::put('/{id}', [ClientController::class, 'update']);
                Route::delete('/{id}', [ClientController::class, 'destroy']);
                
                // Datos relacionados
                Route::get('/{id}/bookings', [ClientController::class, 'bookings']);
                Route::get('/{id}/activity', [ClientController::class, 'activity']);
                Route::get('/{id}/stats', [ClientController::class, 'stats']);
            });

            // ───────────────────────────────────────────────────────────
            // VENTAS
            // ───────────────────────────────────────────────────────────
            Route::prefix('sales')->group(function () {
                Route::get('/summary', [SalesController::class, 'summary']);
                Route::get('/trends', [SalesController::class, 'trends']);
                Route::get('/top-tours', [SalesController::class, 'topTours']);
                Route::get('/income-comparison', [SalesController::class, 'incomeComparison']);
                Route::get('/by-destination', [SalesController::class, 'byDestination']);
                Route::get('/by-agent', [SalesController::class, 'byAgent']);
            });

            // ───────────────────────────────────────────────────────────
            // REPORTES
            // ───────────────────────────────────────────────────────────
            Route::prefix('reports')->group(function () {
                Route::get('/bookings', [ReportController::class, 'bookings']);
                Route::get('/sales', [ReportController::class, 'sales']);
                Route::get('/clients', [ReportController::class, 'clients']);
                Route::get('/conversion-rate', [ReportController::class, 'conversionRate']);
                Route::get('/customer-segmentation', [ReportController::class, 'customerSegmentation']);
                Route::post('/generate', [ReportController::class, 'generate']);
                Route::get('/export/{type}', [ReportController::class, 'export']);
            });

            // ───────────────────────────────────────────────────────────
            // ATENCIÓN AL CLIENTE (Conversaciones)
            // ───────────────────────────────────────────────────────────
            Route::prefix('conversations')->group(function () {
                Route::get('/', [ConversationController::class, 'index']);
                Route::post('/', [ConversationController::class, 'store']);
                Route::get('/{id}', [ConversationController::class, 'show']);
                Route::post('/{id}/messages', [ConversationController::class, 'sendMessage']);
                Route::patch('/{id}/assign', [ConversationController::class, 'assign']);
                Route::patch('/{id}/status', [ConversationController::class, 'updateStatus']);
                Route::patch('/{id}/close', [ConversationController::class, 'close']);
                Route::get('/unread/count', [ConversationController::class, 'unreadCount']);
            });

            // ───────────────────────────────────────────────────────────
            // CONFIGURACIÓN DEL SISTEMA
            // ───────────────────────────────────────────────────────────
            Route::prefix('settings')->group(function () {
                Route::get('/', [SettingsController::class, 'index']);
                Route::put('/', [SettingsController::class, 'update']);
                Route::get('/{group}', [SettingsController::class, 'byGroup']);
            });

            // ───────────────────────────────────────────────────────────
            // GESTIÓN DE USUARIOS (Solo Super Admin)
            // ───────────────────────────────────────────────────────────
            Route::middleware('role:super_admin')->prefix('users')->group(function () {
                Route::get('/', [UserController::class, 'index']);
                Route::post('/', [UserController::class, 'store']);
                Route::get('/{id}', [UserController::class, 'show']);
                Route::put('/{id}', [UserController::class, 'update']);
                Route::delete('/{id}', [UserController::class, 'destroy']);
                Route::patch('/{id}/status', [UserController::class, 'updateStatus']);
                Route::patch('/{id}/roles', [UserController::class, 'updateRoles']);
            });
        });
    });
});

// ═══════════════════════════════════════════════════════════════════════
// WEBHOOKS (Sin autenticación, pero con verificación de firma)
// ═══════════════════════════════════════════════════════════════════════
Route::prefix('webhooks')->group(function () {
    Route::post('/stripe', [UserPaymentController::class, 'stripeWebhook']);
    Route::post('/paypal', [UserPaymentController::class, 'paypalWebhook']);
});

// ═══════════════════════════════════════════════════════════════════════
// FALLBACK - Ruta no encontrada
// ═══════════════════════════════════════════════════════════════════════
Route::fallback(function () {
    return response()->json([
        'error' => 'Endpoint not found',
        'message' => 'The requested API endpoint does not exist',
        'status' => 404
    ], 404);
});