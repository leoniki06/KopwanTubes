@extends('layouts.app')

@section('title', 'Tambah Resep Premium - MoodBite')

@section('content')
<div class="container py-4">

    <div class="page-hero mb-4">
        <h2 class="fw-bold text-white mb-1">Tambah Resep Premium</h2>
        <p class="text-white-50 mb-0">Isi data resep premium dengan mudah ✨</p>
    </div>

    <form action="{{ route('admin.premium-recipes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-soft p-4 mb-4">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="fw-semibold">Nama Resep</label>
                    <input type="text" name="recipe_name" class="form-control rounded-4" required>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Nama Chef</label>
                    <input type="text" name="chef_name" class="form-control rounded-4" required>
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Mood Category</label>
                    <input type="text" name="mood_category" class="form-control rounded-4" placeholder="bahagia / sedih / stres">
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Difficulty</label>
                    <select name="difficulty" class="form-select rounded-4">
                        <option value="mudah">Mudah</option>
                        <option value="sedang">Sedang</option>
                        <option value="sulit">Sulit</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Waktu Masak (menit)</label>
                    <input type="number" name="cooking_time" class="form-control rounded-4" min="1">
                </div>

                <div class="col-md-2">
                    <label class="fw-semibold">Porsi</label>
                    <input type="number" name="servings" class="form-control rounded-4" min="1">
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control rounded-4" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Foto Chef</label>
                    <input type="file" name="chef_photo" class="form-control rounded-4">
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Video URL</label>
                    <input type="text" name="video_url" class="form-control rounded-4">
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
                <i class="fas fa-save me-2"></i> Simpan Resep
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

addIngredient();
addStep();
</script>
@endsection
