<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class PopularCategories extends ChartWidget
{
    protected static ?string $heading = 'Posts by Category';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $categories = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        // Purple color palette
        $purpleColors = [
            'rgba(147, 51, 234, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(192, 132, 252, 0.8)',
            'rgba(216, 180, 254, 0.8)',
            'rgba(233, 213, 255, 0.8)',
            'rgba(126, 34, 206, 0.8)',
            'rgba(107, 33, 168, 0.8)',
            'rgba(88, 28, 135, 0.8)',
            'rgba(196, 181, 253, 0.8)',
            'rgba(167, 139, 250, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $categories->pluck('posts_count')->toArray(),
                    'backgroundColor' => array_slice($purpleColors, 0, $categories->count()),
                    'borderColor' => 'rgba(255, 255, 255, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
