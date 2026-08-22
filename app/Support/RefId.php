<?php

namespace App\Support;

/**
 * Formats human-readable, consistent reference IDs from raw auto-increment
 * primary keys. One static method per entity type — always use these instead
 * of hand-rolling "MJ-XXX-######" strings in views/controllers, so every
 * screen (customer, vendor, admin) and printed label shows the same value
 * for the same row.
 *
 * Example: RefId::order(8521) === 'MJ-ORD-008521'
 */
class RefId
{
    private static function format(string $prefix, $id, int $pad = 6): string
    {
        if ($id === null || $id === '') {
            return '—';
        }

        return 'MJ-' . $prefix . '-' . str_pad((string) $id, $pad, '0', STR_PAD_LEFT);
    }

    public static function customer($id): string
    {
        return self::format('CUS', $id);
    }

    public static function vendor($id): string
    {
        return self::format('VEN', $id);
    }

    public static function product($id): string
    {
        return self::format('PRD', $id);
    }

    public static function order($id): string
    {
        return self::format('ORD', $id);
    }

    public static function payment($id): string
    {
        return self::format('PAY', $id);
    }

    public static function shipment($id): string
    {
        return self::format('SHP', $id);
    }

    public static function return($id): string
    {
        return self::format('RET', $id);
    }

    public static function refund($id): string
    {
        return self::format('REF', $id);
    }

    public static function replacement($id): string
    {
        return self::format('RPL', $id);
    }
}
