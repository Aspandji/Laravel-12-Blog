<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Blog';

    // Set default sorting
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Artikel')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->minLength(3)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                    ->placeholder('Judul Post')
                                    ->columnSpanFull(),

                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpanFull(),

                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(
                                                fn($state, Forms\Set $set) =>
                                                $set('slug', Str::slug($state))
                                            )
                                            ->placeholder('Nama Category'),
                                        TextInput::make('Slug')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        Textarea::make('description')
                                            ->placeholder('Description Category')
                                            ->rows(3)
                                            ->columnSpanFull()
                                    ]),

                                FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('posts')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1'
                                    ])
                                    ->maxSize(5120)
                                    ->nullable()
                                    ->helperText('Max 5MB. Recommended: 1200x630px')
                                    ->dehydrated(fn($state) => filled($state)), //Biar saat EDIT tidak wajib upload ulang

                                Textarea::make('excerpt')
                                    ->label('Deskipsi Singkat')
                                    ->live()
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->helperText('Ringkasan artikel (opsional, akan digunakan untuk meta description jika meta description kosong)'),

                                RichEditor::make('content')
                                    ->label('Deskripsi Artikel')
                                    ->required()
                                    ->minLength(5)
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),

                                Toggle::make('is_published')
                                    ->default(false)
                                    ->live(),

                                DateTimePicker::make('published_at')
                                    ->visible(fn(Get $get) => $get('is_published'))
                                    ->default(now())
                                    ->native(false)
                                // ->required(fn(Forms\Get $get) => $get('is_published'))
                            ])
                            ->columns(2),

                        Tab::make('Optimasi SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->live()
                                    ->helperText('Optimal: 50-60 karakter. Kosongkan untuk menggunakan judul artikel.')
                                    ->maxLength(60)
                                    ->columnSpanFull(),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->live()
                                    ->rows(3)
                                    ->helperText('Optimal: 150-160 karakter. Kosongkan untuk menggunakan deskripsi singkat artikel.')
                                    ->maxLength(160)
                                    ->columnSpanFull(),

                                TextInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->placeholder('Laravel, PHP')
                                    ->helperText('Pisahkan dengan koma. Contoh: laravel, php, web development'),

                                Placeholder::make('seo_preview')
                                    ->label('Preview Google Search')
                                    ->content(function (Get $get) {
                                        $title = $get('meta_title') ?: $get('title') ?: 'Judul Artikel';
                                        $description = $get('meta_description') ?: $get('excerpt') ?: 'Deskripsi artikel akan muncul disini';
                                        $slug = $get('slug') ?: 'artikel-slug';

                                        return new HtmlString("
                                            <div style='padding:16px;border:1px solid #e5e7eb;border-radius:8px;background:#fff'>
                                                <div style='font-size:12px;color:#2563eb'>
                                                    69dev.my.id › post › {$slug}
                                                </div>
                                                <div style='font-size:18px;color:#1e40af;font-weight:500;margin-top:4px'>
                                                    {$title}
                                                </div>
                                                <div style='font-size:14px;color:#4b5563;margin-top:4px'>
                                                    {$description}
                                                </div>
                                            </div>
                                        ");
                                    })
                            ])
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->placeholder('All post')
                    ->trueLabel('Published only')
                    ->falseLabel('Draft only'),
                Filter::make('published_at')
                    ->form([
                        DatePicker::make('published_from')
                            ->label('Published from'),
                        DatePicker::make('published_until')
                            ->label('Published until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn($q, $date) =>
                                $q->whereDate('published_at', '>=', $date)
                            )
                            ->when(
                                $data['published_until'],
                                fn($q, $date) =>
                                $q->whereDate('published_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['published_form'] ?? null) {
                            $indicators[] = Indicator::make('Published from' . Carbon::parse($data['published_from'])->toFormattedDateString())->removeField('published_from');
                        }
                        if ($data['published_until'] ?? null) {
                            $indicators[] = Indicator::make('Published until' . Carbon::parse($data['published_until'])->toFormattedDateString())->removeField('published_until');
                        }
                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_published' => true, 'published_at' => now()]);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_published' => false]);
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc') //default post terbaru
            ->poll('30s') //Auto-refresh setiap 30 detik
            ->striped()
            ->paginated(([10, 25, 50, 100])) // Pagination options
            ->defaultPaginationPageOption(10); //Default 10 per page; 
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
