<div class="mb-2">
    <form method="GET" class="d-inline-flex align-items-center">
        @foreach(request()->except(['per_page', 'page']) as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <label for="per-page" class="me-2">Items per page</label>
        <select id="per-page" name="per_page" class="form-select w-auto" onchange="this.form.submit()">
            @foreach (($options ?? [5,10,20,50]) as $option)
                <option value="{{ $option }}" @selected((int) request('per_page', $default ?? 10) === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </form>
</div>

