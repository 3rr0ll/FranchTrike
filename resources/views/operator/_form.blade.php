<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Last Name</label>
        <input type="text" name="last_name" value="{{ old('last_name', $operator->last_name ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">First Name</label>
        <input type="text" name="first_name" value="{{ old('first_name', $operator->first_name ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Middle Initial</label>
        <input type="text" name="middle_initial" value="{{ old('middle_initial', $operator->middle_initial ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded">
    </div>

    <div>
        <label class="block text-sm font-medium">Barangay</label>
        <input type="text" name="barangay" value="{{ old('barangay', $operator->barangay ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Municipality</label>
        <input type="text" name="municipality" value="{{ old('municipality', $operator->municipality ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Province</label>
        <input type="text" name="province" value="{{ old('province', $operator->province ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Birth Date</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', $operator->birth_date ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Age</label>
        <input type="number" name="age" value="{{ old('age', $operator->age ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Sex</label>
        <select name="sex" class="w-full mt-1 border-gray-300 rounded" required>
            <option value="Male" @selected(old('sex', $operator->sex ?? '') === 'Male')>Male</option>
            <option value="Female" @selected(old('sex', $operator->sex ?? '') === 'Female')>Female</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Civil Status</label>
        <input type="text" name="civil_status" value="{{ old('civil_status', $operator->civil_status ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Contact Number</label>
        <input type="text" name="contact_no" value="{{ old('contact_no', $operator->contact_no ?? '') }}"
            class="w-full mt-1 border-gray-300 rounded" required>
    </div>
</div>