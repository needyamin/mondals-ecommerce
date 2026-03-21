<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseCrudController extends Controller
{
    protected string $model;
    protected string $viewPrefix;
    protected string $routePrefix;
    protected int $perPage = 15;
    protected array $with = [];
    protected array $searchable = ['name'];

    /**
     * List all records with search & pagination.
     */
    public function index(Request $request)
    {
        $query = $this->model::query()->with($this->with);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchable as $i => $col) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->$method($col, 'LIKE', "%{$search}%");
                }
            });
        }

        // Apply any extra filters from child controllers
        $query = $this->applyFilters($query, $request);

        $items = $query->latest()->paginate($this->perPage)->withQueryString();

        return view("{$this->viewPrefix}.index", [
            'items' => $items,
            'search' => $search,
        ]);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view("{$this->viewPrefix}.create", $this->formData());
    }

    /**
     * Store new record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        $validated = $this->beforeSave($validated, $request);

        $item = $this->model::create($validated);
        $this->afterSave($item, $request);

        return redirect()->route("{$this->routePrefix}.index")
            ->with('success', $this->modelLabel() . ' created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        $item = $this->model::findOrFail($id);

        return view("{$this->viewPrefix}.edit", array_merge(
            ['item' => $item],
            $this->formData($item)
        ));
    }

    /**
     * Update record.
     */
    public function update(Request $request, int $id)
    {
        $item = $this->model::findOrFail($id);
        $validated = $request->validate($this->validationRules($item));
        $validated = $this->beforeSave($validated, $request, $item);

        $item->update($validated);
        $this->afterSave($item, $request);

        return redirect()->route("{$this->routePrefix}.index")
            ->with('success', $this->modelLabel() . ' updated successfully.');
    }

    /**
     * Delete record.
     */
    public function destroy(int $id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();

        return redirect()->route("{$this->routePrefix}.index")
            ->with('success', $this->modelLabel() . ' deleted successfully.');
    }

    // ── Override hooks ──

    protected function validationRules(?Model $item = null): array { return []; }
    protected function formData(?Model $item = null): array { return []; }
    protected function beforeSave(array $data, Request $request, ?Model $item = null): array { return $data; }
    protected function afterSave(Model $item, Request $request): void {}
    protected function applyFilters($query, Request $request) { return $query; }

    protected function modelLabel(): string
    {
        return Str::headline(class_basename($this->model));
    }
}
