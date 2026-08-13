<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasCode
{
    /**
     * Boot the trait.
     */
    protected static function bootHasCode(): void
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = $model->generateCode();
            }
        });
    }

    /**
     * Generate a unique code for the model.
     * Override this method in your model to customize.
     */
    public function generateCode(): string
    {
        $prefix = $this->getCodePrefix();
        $length = $this->getCodeLength();
        $field = $this->getCodeField();

        // Obtener el último código
        $lastCode = static::where($field, 'ILIKE', $prefix . '%')
            ->orderBy($field, 'desc')
            ->value($field);

        if ($lastCode) {
            // Extraer el número secuencial
            $number = (int) substr($lastCode, strlen($prefix));
            $number++;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Get the prefix for the code.
     * Override this method in your model.
     */
    protected function getCodePrefix(): string
    {
        return property_exists($this, 'codePrefix')
            ? $this->codePrefix
            : strtoupper(substr(class_basename($this), 0, 3)) . '-';
    }

    /**
     * Get the length of the numeric part.
     * Override this method in your model.
     */
    protected function getCodeLength(): int
    {
        return property_exists($this, 'codeLength')
            ? $this->codeLength
            : 6;
    }

    /**
     * Get the field name for the code.
     * Override this method in your model.
     */
    protected function getCodeField(): string
    {
        return property_exists($this, 'codeField')
            ? $this->codeField
            : 'code';
    }

    /**
     * Get the next code number without prefix.
     */
    public function getNextCodeNumber(): int
    {
        $prefix = $this->getCodePrefix();
        $field = $this->getCodeField();

        $lastCode = static::where($field, 'LIKE', $prefix . '%')
            ->orderBy($field, 'desc')
            ->value($field);

        if ($lastCode) {
            return (int) substr($lastCode, strlen($prefix)) + 1;
        }

        return 1;
    }

    /**
     * Regenerate code for existing record.
     */
    public function regenerateCode(): bool
    {
        $this->code = $this->generateCode();
        return $this->save();
    }

    /**
     * Scope to find by code.
     */
    public function scopeWhereCode($query, $code)
    {
        return $query->where($this->getCodeField(), $code);
    }

    /**
     * Scope to search by code.
     */
    public function scopeSearchByCode($query, $search)
    {
        return $query->where($this->getCodeField(), 'LIKE', '%' . $search . '%');
    }
}
