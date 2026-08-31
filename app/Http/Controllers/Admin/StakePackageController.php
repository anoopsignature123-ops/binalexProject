<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StakePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StakePackageController extends Controller
{
    public function index(): View
    {
        return view('admin.stake-packages.index', [
            'packages' => StakePackage::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.stake-packages.create', ['package' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        StakePackage::create($this->validatedData($request));

        return redirect()->route('admin.stake-packages.index')->with('success', 'Stake package created successfully.');
    }

    public function edit(StakePackage $stakePackage): View
    {
        return view('admin.stake-packages.edit', ['package' => $stakePackage]);
    }

    public function update(Request $request, StakePackage $stakePackage): RedirectResponse
    {
        $stakePackage->update($this->validatedData($request));

        return redirect()->route('admin.stake-packages.index')->with('success', 'Stake package updated successfully.');
    }

    public function destroy(StakePackage $stakePackage): RedirectResponse
    {
        $stakePackage->delete();

        return redirect()->route('admin.stake-packages.index')->with('success', 'Stake package deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'amount_usd' => ['required', 'numeric', 'gt:0'],
            'coins' => ['required', 'numeric', 'gt:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'return_multiplier' => ['required', 'numeric', 'gte:1'],
            'direct_income_percent' => ['required', 'numeric', 'gte:0', 'lte:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
