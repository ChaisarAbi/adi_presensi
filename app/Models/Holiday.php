<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'tanggal',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Check if a date is a holiday
     */
    public static function isHoliday($date): bool
    {
        return self::where('tanggal', $date)->exists();
    }

    /**
     * Check if a date is a school day (Monday-Friday and not holiday)
     */
    public static function isSchoolDay($date): bool
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        
        // Monday-Friday (1-5) are school days
        $isWeekday = $carbonDate->dayOfWeekIso >= 1 && $carbonDate->dayOfWeekIso <= 5;
        
        // Not a holiday
        $isNotHoliday = !self::isHoliday($date);
        
        return $isWeekday && $isNotHoliday;
    }

    /**
     * Get school days count between two dates
     */
    public static function countSchoolDays($startDate, $endDate): int
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        $count = 0;

        // Get all holidays in the period for efficient checking
        $holidays = self::whereBetween('tanggal', [$start, $end])
            ->pluck('tanggal')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->toDateString();
            })
            ->toArray();

        $current = $start->copy();
        while ($current->lte($end)) {
            // Monday-Friday (1-5)
            if ($current->dayOfWeekIso >= 1 && $current->dayOfWeekIso <= 5) {
                // Check if not holiday
                if (!in_array($current->toDateString(), $holidays)) {
                    $count++;
                }
            }
            $current->addDay();
        }

        return $count;
    }
}
