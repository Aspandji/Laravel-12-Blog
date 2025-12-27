<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostsChart extends ChartWidget
{
    protected static ?string $heading = 'Posts Created Over Time';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 3 Months',
            '365' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = (int) $this->filter;

        $data = Trend::model(Post::class)
            ->between(
                start: now()->subDays($activeFilter),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Posts created',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)',
                    'borderColor' => 'rgb(147, 51, 234)',
                    'pointBackgroundColor' => 'rgb(147, 51, 234)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(147, 51, 234)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
