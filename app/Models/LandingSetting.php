<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    use HasFactory;

    public const FLYER_TITLE_SEGMENTS_KEY = 'flyer_section_title_segments';

    public const FLYER_DESCRIPTION_SEGMENTS_KEY = 'flyer_section_description_segments';

    protected $fillable = ['key', 'value'];

    /**
     * @return array<int, array{text: string, color: ?string}>
     */
    public static function flyerTitleSegments(): array
    {
        return static::segmentsFor(
            self::FLYER_TITLE_SEGMENTS_KEY,
            'Temukan Jalur Belajar yang Tepat untukmu'
        );
    }

    /**
     * @return array<int, array{text: string, color: ?string}>
     */
    public static function flyerDescriptionSegments(): array
    {
        return static::segmentsFor(
            self::FLYER_DESCRIPTION_SEGMENTS_KEY,
            'Setiap program dirancang untuk tujuan yang berbeda. Pilih yang paling sesuai dengan mimpimu.'
        );
    }

    /**
     * @return array<int, array{text: string, color: ?string}>
     */
    private static function segmentsFor(string $key, string $defaultText): array
    {
        $value = static::query()->where('key', $key)->value('value');

        if ($value === null) {
            return [['text' => $defaultText, 'color' => null]];
        }

        $segments = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($segments) || ! array_is_list($segments)) {
            throw new \UnexpectedValueException("Invalid text segments stored for {$key}.");
        }

        return $segments;
    }
}
