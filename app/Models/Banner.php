<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'title_segments',
        'subtitle',
        'image',
        'wa_number',
        'wa_button_text',
        'order',
    ];

    protected $casts = [
        'title_segments' => 'array',
    ];

    /**
     * @return array<int, array{text: string, color: ?string}>
     */
    public function getTitleSegmentsForDisplayAttribute(): array
    {
        $segments = $this->title_segments;

        if (! is_array($segments) || $segments === []) {
            return [['text' => (string) $this->title, 'color' => null]];
        }

        $displaySegments = [];

        foreach ($segments as $segment) {
            if (! is_array($segment) || ! is_string($segment['text'] ?? null)) {
                return [['text' => (string) $this->title, 'color' => null]];
            }

            $color = $segment['color'] ?? null;

            if ($color !== null && (! is_string($color) || ! preg_match('/\A#[0-9a-fA-F]{6}\z/', $color))) {
                $color = null;
            }

            $displaySegments[] = [
                'text' => $segment['text'],
                'color' => $color,
            ];
        }

        if (implode('', array_column($displaySegments, 'text')) !== $this->title) {
            return [['text' => (string) $this->title, 'color' => null]];
        }

        return $displaySegments;
    }
}
