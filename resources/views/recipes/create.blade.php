@extends('layouts.main')
@section('body-id', 'recipes')

@section('content')
<div class="p-5">

    <form class="p-5 border rounded bg-white" action="{{ route('recipes.create') }}" method="post">
        @csrf
        <h2 class="mb-5">{{ _gettext('Add Recipe') }}</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{ _gettext('An error has occurred') }}</h4>
            <p>{{ _gettext('Please fill out the required fields below.') }}</p>
        </div>
    @endif
        <div class="mb-3 required">
            <label for="name" class="form-label">{{ _gettext('Name') }}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>
        <div class="mb-3 required">
            <label for="thumbnail" class="form-label">{{ _gettext('Thumbnail') }}</label>
            <div>
            @for ($i=1; $i<=6; $i++)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="thumbnail" id="recipes_{{ $i }}" value="recipes_{{ $i }}.jpg">
                    <label class="form-check-label" for="recipes_{{ $i }}"><img src="{{ asset('img/recipes_'.$i.'.jpg') }}"></label>
                </div>
            @endfor
            </div>
        </div>
        <div class="mb-3 required">
            <label for="category" class="form-label">{{ _gettext('Category') }}</label>
            <div class="row">
                <div class="col-9">
                    <select class="form-select" id="category" name="category">
                        <option></option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <a href="{{ route('recipes.categories.create') }}">{{ _gettext('New Category') }}</a>
                </div>
            </div>
        </div>
        <div class="mb-3 required">
            <label for="ingredients[]" class="form-label">{{ _gettext('Ingredients') }}</label>
            <input type="text" class="form-control mb-2" id="ingredients[]" name="ingredients[]" value="{{ old('ingredients') }}">
            <div class="add-ingredient form-text">{{ _gettext('Add ingredient') }}</div>
        </div>
        <div class="mb-5 required">
            <label for="directions" class="form-label">{{ _gettext('Directions') }}</label>
            <x-text-editor :name="'directions'" :remove="['images', 'emojis', 'submit']"/>
        </div>
        <div class="">
            <button class="btn btn-success px-5" type="submit" id="submit" name="submit">
                <i class="bi-check-square me-1"></i>
                {{ _gettext('Add') }}
            </button>
        </div>
    </form>

</div>
<style>
.form-check-label > img
{
    width: 75px;
}
.add-ingredient:hover
{
    cursor: pointer;
    text-decoration: underline;
}
</style>
<script>
$(function() {
    $('.add-ingredient').click(function() {
        let $add        = $(this);
        let $ingredient = $add.prev('input').clone();

        $ingredient.val('');
        $add.before($ingredient);
    });
});
</script>
@endsection
