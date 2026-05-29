<?php

namespace App\Services;

class InvoiceLayoutConverter
{
    /**
     * A4 paper inner width at 96dpi minus padding (794 - 100 = 694px)
     */
    const A4_INNER_WIDTH = 694;

    public static function toRows(array $blocks): array
    {
        // Filter only visible blocks
        $visible = array_filter($blocks, fn($b) => $b['visible'] ?? true);

        if (empty($visible)) return [];

        // Sort by Y first, then X
        usort($visible, function ($a, $b) {
            $yDiff = ($a['y'] ?? 0) - ($b['y'] ?? 0);
            return $yDiff !== 0 ? $yDiff : ($a['x'] ?? 0) - ($b['x'] ?? 0);
        });

        $rows   = [];
        // Use height-aware tolerance:
        // Two blocks are in the same row if their Y values overlap vertically
        foreach ($visible as $block) {
            $blockY = $block['y'] ?? 0;
            $blockH = $block['h'] ?? 80;
            $placed = false;

            foreach ($rows as &$row) {
                $rowY = $row['y'];
                $rowH = $row['maxH'];

                // Overlap check — do these two blocks share vertical space?
                $overlapTop    = max($blockY, $rowY);
                $overlapBottom = min($blockY + $blockH, $rowY + $rowH);

                if ($overlapBottom > $overlapTop) {
                    // They overlap vertically → same row
                    $row['blocks'][] = $block;
                    $row['y']    = min($row['y'], $blockY);
                    $row['maxH'] = max($rowH, $blockH);
                    $placed = true;
                    break;
                }
            }
            unset($row);

            if (!$placed) {
                $rows[] = [
                    'y'      => $blockY,
                    'maxH'   => $blockH,
                    'blocks' => [$block],
                ];
            }
        }

        // Sort rows by Y position
        usort($rows, fn($a, $b) => $a['y'] - $b['y']);

        // Within each row: sort blocks by X, calculate column widths
        foreach ($rows as &$row) {
            usort($row['blocks'], fn($a, $b) => ($a['x'] ?? 0) - ($b['x'] ?? 0));

            // Use saved widths — clamp total to A4 inner width
            $savedTotal = array_sum(array_column($row['blocks'], 'w'));
            $row['totalW'] = $savedTotal > 0 ? $savedTotal : self::A4_INNER_WIDTH;
        }
        unset($row);

        return $rows;
    }
}