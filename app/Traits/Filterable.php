<?php

namespace App\Traits;

trait Filterable
{
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $field => $value) {
            if (is_null($value) || $value === '') continue;

            match (true) {
                method_exists($this, 'filter' . ucfirst($field))
                    => $this->{'filter' . ucfirst($field)}($query, $value),
                str_ends_with($field, '_min')
                    => $query->where(str_replace('_min', '', $field), '>=', $value),
                str_ends_with($field, '_max')
                    => $query->where(str_replace('_max', '', $field), '<=', $value),
                $field === 'search'
                    => $query->where(fn($q) => collect($this->searchable ?? ['name'])
                        ->each(fn($col, $i) => $i === 0
                            ? $q->where($col, 'LIKE', "%{$value}%")
                            : $q->orWhere($col, 'LIKE', "%{$value}%"))),
                default
                    => $query->where($field, $value),
            };
        }

        return $query;
    }

    public function scopeSorted($query, ?string $sort = null, string $default = 'created_at')
    {
        $sort ??= $default;
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        return $query->orderBy($column, $direction);
    }
}
