<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                if (!auth()->check()) return;

                AuditLog::create([
                    'user_id'    => auth()->id(),
                    'action'     => $event,
                    'model_type' => get_class($model),
                    'model_id'   => $model->id,
                    'old_values' => $event === 'updated' ? $model->getOriginal() : null,
                    'new_values' => $event !== 'deleted' ? $model->getAttributes() : null,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
        }
    }
}
