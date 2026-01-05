@extends('layouts.app')

@section('title', 'Edit Resep Premium - MoodBite')

@section('content')
@php
    // pastikan ingredients & step_by_step jadi array
    $ingredients = is_array($recipe->ingredients) ? $recipe->ingredients : json_decode($recipe->ingredients, true);
    $steps = is_array($recipe->step_by_step) ? $recipe->step_by_step : json_decode($recipe->step_by_step, true);

    $ingredients = $ingredients ?: [];
    $steps = $steps ?: [];
@endphp

<div class="container py-4">

    <div class="page-hero mb-4">
        <h2 class="fw-bold text-white mb-1">Edit Resep Premium</h2>
        <p class="text-white-50 mb-0">Perbarui data resep premium dengan mudah ✨</p>
    </div>

    <form action="{{ route('admin.premium-recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-soft p-4 mb-4">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-semibold">Nama Resep</label>
                    <input type="text" name="recipe_name" class="form-control rounded-4" value="{{ old('recipe_name', $recipe->recipe_name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Nama Chef</label>
                    <input type="text" name="chef_name" class="form-control rounded-4" value="{{ old('chef_name', $recipe->chef_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Mood Category</label>
                    <input type="text" name="mood_category" class="form-control rounded-4" value="{{ old('mood_category', $recipe->mood_category) }}" placeholder="bahagia / sedih / stres">
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Difficulty</label>
                    <select name="difficulty" class="form-select rounded-4">
                        @php $diff = old('difficulty', $recipe->difficulty); @endphp
                        <option value="mudah" {{ $diff == 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ $diff == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ $diff == 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Waktu Masak (menit)</label>
                    <input type="number" name="cooking_time" class="form-control rounded-4" min="1" value="{{ old('cooking_time', $recipe->cooking_time) }}">
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Porsi</label>
                    <input type="number" name="servings" class="form-control rounded-4" min="1" value="{{ old('servings', $recipe->servings) }}">
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control rounded-4" rows="3">{{ old('description', $recipe->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Foto Chef (Opsional)</label>
                    <input type="file" name="chef_photo" class="form-control rounded-4">

                    @if($recipe->chef_photo)
                        <div class="mt-2 small text-muted">
                            Foto saat ini:
                            <a href="{{ asset('storage/'.$recipe->chef_photo) }}" target="_blank">Lihat</a>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Video URL</label>
                    <input type="text" name="video_url" class="form-control rounded-4" value="{{ old('video_url', $recipe->video_url) }}">
                </div>

                <div class="col-md-12 mt-2">
                    <label class="fw-semibold">Status Resep</label>
                    <select name="is_active" class="form-select rounded-4">
                        <option value="1" {{ old('is_active', $recipe->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $recipe->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

        </div>

        {{-- ✅ INGREDIENTS --}}
        <div class="card-soft p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-pink">Ingredients</h5>
                <button type="button" class="btn btn-add" onclick="addIngredient()">
                    + Tambah Bahan
                </button>
            </div>

            <div id="ingredients-wrapper"></div>
        </div>

        {{-- ✅ STEP BY STEP --}}
        <div class="card-soft p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-pink">Step by Step</h5>
                <button type="button" class="btn btn-add" onclick="addStep()">
                    + Tambah Step
                </button>
            </div>

            <div id="steps-wrapper"></div>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.premium-recipes.index') }}" class="btn btn-outline-secondary rounded-4 px-4">Batal</a>
            <button class="btn btn-premium px-4 rounded-4">
                <i class="fas fa-save me-2"></i> Update Resep
            </button>
        </div>

    </form>

</div>

<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
}
.page-hero{
    border-radius: 20px;
    padding: 20px;
    background: linear-gradient(135deg, var(--pink), var(--pink2));
}
.card-soft{
    border-radius: 20px;
    background:#fff;
    border:1px solid rgba(255,107,139,.15);
    box-shadow:0 10px 30px rgba(0,0,0,.05);
}
.text-pink{ color: var(--pink); }
.btn-premium{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border:none;
    font-weight:800;
    color:#fff;
}
.btn-premium:hover{ filter:brightness(.95); color:#fff; }
.btn-add{
    background: rgba(255,107,139,.12);
    color: var(--pink);
    border:1px solid rgba(255,107,139,.25);
    font-weight:800;
    border-radius: 14px;
    padding: 8px 14px;
}
.btn-add:hover{ background: rgba(255,107,139,.18); }
.row-item{
    background: rgba(255,107,139,.05);
    border:1px solid rgba(255,107,139,.15);
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 12px;
}
.btn-remove{
    background: rgba(220,53,69,.12);
    border:1px solid rgba(220,53,69,.25);
    color:#dc3545;
    border-radius: 12px;
    font-weight:800;
}
.btn-remove:hover{ background: rgba(220,53,69,.20); }
</style>

<script>
let ingredientIndex = 0;
let stepIndex = 0;

function addIngredient(name="", quantity="", unit="") {
    const wrapper = document.getElementById("ingredients-wrapper");

    wrapper.insertAdjacentHTML("beforeend", `
        <div class="row-item d-flex gap-2 align-items-center" id="ingredient-${ingredientIndex}">
            <input type="text" name="ingredients[${ingredientIndex}][name]" class="form-control rounded-4" placeholder="Nama bahan" value="${name}" required>
            <input type="text" name="ingredients[${ingredientIndex}][quantity]" class="form-control rounded-4" style="max-width:120px" placeholder="Qty" value="${quantity}">
            <input type="text" name="ingredients[${ingredientIndex}][unit]" class="form-control rounded-4" style="max-width:120px" placeholder="Unit" value="${unit}">
            <button type="button" class="btn btn-remove" onclick="removeIngredient(${ingredientIndex})">
                ✕
            </button>
        </div>
    `);
    ingredientIndex++;
}

function removeIngredient(index){
    document.getElementById("ingredient-"+index).remove();
}

function addStep(instruction="", duration="", tip=""){
    const wrapper = document.getElementById("steps-wrapper");

    wrapper.insertAdjacentHTML("beforeend", `
        <div class="row-item" id="step-${stepIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Step ${stepIndex+1}</strong>
                <button type="button" class="btn btn-remove" onclick="removeStep(${stepIndex})">✕</button>
            </div>
            <textarea name="step_by_step[${stepIndex}][instruction]" class="form-control rounded-4 mb-2" rows="2" placeholder="Instruksi..." required>${instruction}</textarea>
            <input type="text" name="step_by_step[${stepIndex}][duration]" class="form-control rounded-4 mb-2" placeholder="Durasi (opsional)" value="${duration}">
            <input type="text" name="step_by_step[${stepIndex}][tip]" class="form-control rounded-4" placeholder="Tips (opsional)" value="${tip}">
        </div>
    `);

    stepIndex++;
}

function removeStep(index){
    document.getElementById("step-"+index).remove();
}

// ✅ isi data lama dari PHP ke JS
const oldIngredients = @json($ingredients);
const oldSteps = @json($steps);

if(oldIngredients.length){
    oldIngredients.forEach(i => addIngredient(i.name ?? "", i.quantity ?? "", i.unit ?? ""));
}else{
    addIngredient();
}

if(oldSteps.length){
    oldSteps.forEach(s => addStep(s.instruction ?? "", s.duration ?? "", s.tip ?? ""));
}else{
    addStep();
}
</script>
@endsection