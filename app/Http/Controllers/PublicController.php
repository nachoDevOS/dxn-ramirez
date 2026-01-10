<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\WhatsappJob;
use App\Models\VaccinationRecord;

class PublicController extends Controller
{
    public function reminderNotificate()
    {
        $clinicName = setting('admin.title');

        $servidor = setting('whatsapp.servidores');
        $sessionId = setting('whatsapp.session');

        $soliciondigitalServidor = setting('solucion-digital.servidorWhatsapp');
        $soliciondigitalSessionId = setting('solucion-digital.sessionWhatsapp');
        $developerPhone = setting('solucion-digital.developerPhone', '67285914'); // Fallback por si no está en settings

        $countReminders = 0;
        $countVaccinationRecords = 0;
        $countDesparasitations = 0;

        // Notificación de inicio de servicio al desarrollador/admin
        if ($soliciondigitalServidor && $soliciondigitalSessionId && $developerPhone) {
            $now = now()->format('d/m/Y h:i A');
            $notificationMessage = "¡Hola Ing. Ignacio! 🚀\n\n" .
                "El servicio de recordatorios para *{$clinicName}* se ha iniciado correctamente.\n\n" .
                "*Detalles de la ejecución:*\n" .
                "*Hora:* {$now}\n" .
                "*Endpoint:* `https://consultorioveterinariocortez.com/reminder/notificate`";

            WhatsappJob::dispatch($soliciondigitalServidor, $soliciondigitalSessionId, '591', $developerPhone, $notificationMessage, 'nachoDevOs');
        }

        $reminders = Reminder::with(['pet.person'])
                ->whereDate('date', '=', date('Y-m-d'))
                ->get();

        foreach ($reminders as $reminder) {
            $owner = $reminder->pet->person;

            $ownerName = ucwords(strtolower($owner->first_name.' '.$owner->paternal_surname));
            $petName = ucwords(strtolower($reminder->pet->name));
            

            $message = "¡Hola, {$ownerName}! 👋\n\n" .
                       "En *{$clinicName}* sabemos que el bienestar de *{$petName}* es lo más importante para ti, ¡y para nosotros también! ❤️\n\n" .
                       "Te enviamos un recordatorio amigable sobre su próximo cuidado:\n\n" .
                       "📝 *Motivo:* {$reminder->observation}\n" .
                       "🗓️ *Fecha:* " . \Carbon\Carbon::parse($reminder->date)->format('d/m/Y') . "\n" .
                       "⏰ *Hora:* " . \Carbon\Carbon::parse($reminder->time)->format('h:i A') . "\n\n" .
                       "¡Los esperamos con mucho cariño para seguir cuidando de tu mascota!\n\n" .
                       "Atentamente,\n*El equipo de {$clinicName}* 🐾";

            if($owner->country_code &&$owner->phone && $servidor && $sessionId)
            {
                WhatsappJob::dispatch($servidor, $sessionId, $owner->country_code, $owner->phone, $message, 'Envio de Recordatorio Automatico');
                $countReminders++;
            }          
        }

        $vaccinationRecords = VaccinationRecord::with(['pet.person'])
                ->whereDate('nextDate', '=', date('Y-m-d'))
                ->get();
        foreach ($vaccinationRecords as $vaccination) {
            $owner = $vaccination->pet->person;

            // Construir el mensaje detallado
            $ownerName = ucwords(strtolower($owner->first_name.' '.$owner->paternal_surname));
            $petName = ucwords(strtolower($vaccination->pet->name));

            $typeText = $vaccination->type == 'Vacunas' ? 'vacuna' : 'desparasitación';
            $vaccineName = $vaccination->vaccine;


            $message = "¡Hola, {$ownerName}! 👋\n\n" .
                       "En *{$clinicName}* sabemos que el bienestar de *{$petName}* es lo más importante para ti, ¡y para nosotros también! ❤️\n\n" .
                       "Te enviamos un recordatorio amigable sobre su próxima {$typeText}:\n\n" .
                    //    "💉 *Producto:* {$vaccineName}\n" .
                       "🗓️ *Fecha:* " . \Carbon\Carbon::parse($vaccination->nextDate)->format('d/m/Y') . "\n\n" .
                       "¡Los esperamos con mucho cariño para seguir cuidando de tu mascota!\n\n" .
                       "Atentamente,\n*El equipo de {$clinicName}* 🐾";

            if($owner->country_code && $owner->phone && $servidor && $sessionId)
            {
                WhatsappJob::dispatch($servidor, $sessionId, $owner->country_code, $owner->phone, $message, 'Envio de Recordatorio Automatico');
                if($vaccination->type == 'Vacunas')
                {
                    $countVaccinationRecords++;
                }
                else
                {
                    $countDesparasitations++;
                }
            }
        }

        if ($soliciondigitalServidor && $soliciondigitalSessionId && $developerPhone) {
            
            $now = now()->format('d/m/Y h:i A');
            $notificationMessage = "¡Hola Ing. Ignacio! 🚀\n\n" .
                "El servicio de recordatorios para *{$clinicName}* ha finalizado correctamente.\n\n" .
                "*Resumen de envíos:*\n" .
                "🔔 Recordatorios: {$countReminders}\n" .
                "💉 Vacunación: {$countVaccinationRecords}\n" .
                "💊 Desparasitación/Vitaminas: {$countDesparasitations}\n\n" .
                "*Detalles de la ejecución:*\n" .
                "*Hora:* {$now}\n" .
                "*Endpoint:* `https://consultorioveterinariocortez.com/reminder/notificate`";

            WhatsappJob::dispatch($soliciondigitalServidor, $soliciondigitalSessionId, '591', $developerPhone, $notificationMessage, 'nachoDevOs');
        }

        return 'OK';
    }

}
