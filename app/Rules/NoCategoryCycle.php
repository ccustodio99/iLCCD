<?php

namespace App\Rules;

use App\Models\InventoryCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoCategoryCycle implements ValidationRule
{
    private ?InventoryCategory $category;

    public function __construct(?InventoryCategory $category = null)
    {
        $this->category = $category;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) {
            return;
        }

        $parentId = (int) $value;

        if ($this->category && $parentId === $this->category->id) {
            $fail('Parent category cannot be itself.');

            return;
        }

        if ($this->category) {
            while ($parentId) {
                if ($parentId === $this->category->id) {
                    $fail('Parent category cannot be one of its descendants.');

                    return;
                }

                $parent = InventoryCategory::find($parentId);
                if (! $parent) {
                    break;
                }
                $parentId = $parent->parent_id;
            }
        }
    }
}
