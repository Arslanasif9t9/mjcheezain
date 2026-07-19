<?php

namespace App\Services\MjGuide;

/**
 * The provider-agnostic search protocol between MJGuider and the model:
 *   <<MJG_SEARCH{"q":"perfume long lasting","sort":"relevance|rating|sales"}>>  (model asks for a DB search)
 *   <<MJG_PICK[12,45]>>                                                        (model picks candidate ids, max 3)
 * Plain-text tokens so the same prompt works on Gemini AND Grok (no native
 * function-calling — failover would break mid-tool-call across the two APIs).
 */
class Directives
{
    public const MAX_PICKS = 3;

    private const SORTS = ['relevance', 'rating', 'sales'];

    /** First <<MJG_SEARCH{...}>> in the text, validated — or null (treat text as a plain reply). */
    public static function parseSearch(string $text): ?array
    {
        if (! preg_match('/<<MJG_SEARCH\s*(\{.*?\})\s*>>/su', $text, $m)) {
            return null;
        }

        $data = json_decode($m[1], true);
        if (! is_array($data) || ! isset($data['q']) || ! is_string($data['q'])) {
            return null;
        }

        $q = trim($data['q']);
        if (mb_strlen($q) < 2 || mb_strlen($q) > 80) {
            return null;
        }

        $sort = $data['sort'] ?? 'relevance';
        if (! is_string($sort) || ! in_array($sort, self::SORTS, true)) {
            $sort = 'relevance';
        }

        return ['q' => $q, 'sort' => $sort];
    }

    /** Ids from the first <<MJG_PICK[...]>>: deduped, restricted to $validIds, max 3. */
    public static function parsePicks(string $text, array $validIds): array
    {
        if (! preg_match('/<<MJG_PICK\s*\[([0-9,\s]*)\]\s*>>/su', $text, $m)) {
            return [];
        }

        $picks = [];
        foreach (explode(',', $m[1]) as $piece) {
            $id = (int) trim($piece);
            if ($id > 0 && in_array($id, $validIds, true) && ! in_array($id, $picks, true)) {
                $picks[] = $id;
                if (count($picks) >= self::MAX_PICKS) {
                    break;
                }
            }
        }

        return $picks;
    }

    /** Remove every protocol artifact from user-visible text. */
    public static function strip(string $text): string
    {
        $text = preg_replace('/<<MJG_SEARCH\s*\{.*?\}\s*>>/su', '', $text);
        $text = preg_replace('/<<MJG_PICK\s*\[[0-9,\s]*\]\s*>>/su', '', $text);
        // any half-formed leftovers (model typos, output truncated mid-token, single '>')
        $text = preg_replace('/<<MJG_[^>]*>{0,2}/su', '', $text);

        return trim($text);
    }

    /** Defuse protocol tokens inside USER-supplied text (message + client context) before it reaches the model. */
    public static function neutralize(string $text): string
    {
        // drop the extra '<' run so no input (e.g. '<<<MJG_') can collapse back into a live '<<MJG_' token
        return preg_replace('/<+(?=<MJG_)/', '', $text);
    }
}
