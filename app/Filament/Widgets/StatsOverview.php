<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $draftPosts = Post::where('is_published', false)->count();
        $totalCategories = Category::count();

        // Post dibuat bulan ini
        $thisMonthPosts = Post::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Simple sparkline data (last 7 days)
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            return Post::whereDate('created_at', now()->subDays($daysAgo)->toDateString())->count();
        })->toArray();

        return [
            Stat::make('Total Posts', $totalPosts)
                ->description("{$thisMonthPosts} created this month")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($last7Days),

            Stat::make('Published Posts', $publishedPosts)
                ->description($totalPosts > 0 ? round(($publishedPosts / $totalPosts) * 100, 1) . '% of total' : '0%')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Draft Posts', $draftPosts)
                ->description('Waiting to be published')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Categories', $totalCategories)
                ->description('Total blog categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
        ];
    }
}
