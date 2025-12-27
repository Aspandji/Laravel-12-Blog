<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPosts extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Latest Post';

    public function table(Table $table): Table
    {
        return $table
            ->query(PostResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar Artikel')
                    ->size(60)
                    ->getStateUsing(fn($record) => $record->featured_image_url),
                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->label('Created'),
            ])
            ->actions([
                Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn($record) => PostResource::getUrl('edit', ['record' => $record])),
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->url(fn($record) => route('blog.show', $record->slug))
                    ->openUrlInNewTab(),
            ]);
    }
}
