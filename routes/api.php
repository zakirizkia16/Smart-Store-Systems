use App\Http\Controllers\WhatsAppController;

Route::post('/whatsapp/webhook', [WhatsAppController::class, 'handleWebhook']);